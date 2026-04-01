<?php

namespace App\Imports\Component\Area;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\ToCollection;

use App\Models\CrmBuildingSpace;

use App\Support\Data\LandAreaData;
use App\Support\Data\ExclusiveAreaData;
use App\Support\Data\AgreedDedicatedAreaSettingData;

use App\Support\Enum\ExclusiveAreaName;

use App\Repositories\Excel\AreaImportRepository;

class Main implements ToCollection
{
    use \App\Support\Trait\Excel\Area\AreaTrait;

    /**
     * 戶別
     * @var int
     */
    public const SPACE_KEY = 0;

    /**
     * 土地專用面積
     * @var int
     */
    public const DEDICATED_KEY = 1;

    /**
     * 土地約定專用面積
     * @var int
     */
    public const AGREEMENT_KEY = 2;

    /**
     * 室內面積
     * @var int
     */
    public const INDOOR_KEY = 3;

    /**
     * 室內陽台面積
     * @var int
     */
    public const AWNING_KEY = 4;

    /**
     * 室內雨遮面積
     * @var int
     */
    public const BALCONY_KEY = 5;

    /**
     * 保存面積
     * @var int
     */
    public const PRESERVATION_KEY = 6;

    /**
     * @param \Illuminate\Support\Collection $spaceData 戶別資料
     */
    public function __construct(
        private readonly Collection $spaceData
    ) {
    }

    public function collection(Collection $rows): void
    {
        $importData = $this->fetchImportData($rows);

        $landAreaImportData = $this->fetchLandAreaImportData($importData);
        $exclusiveAreaImportData = $this->fetchExclusiveAreaImportData($importData);
        $agreedDedicatedAreaSettingImportData = $this->fetchAgreedDedicatedAreaSettingImportData($importData);

        $areaImportRepository = new AreaImportRepository();
        $areaImportRepository->landAreaCreateOrUpdate($landAreaImportData);
        $areaImportRepository->exclusiveAreaCreateOrUpdate($exclusiveAreaImportData);
        $areaImportRepository->agreedDedicatedAreaSettingCreateOrUpdate($agreedDedicatedAreaSettingImportData);
    }

    /**
     * 取得匯入資料
     *
     * @param \Illuminate\Support\Collection $rows\
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchImportData(Collection $rows): Collection
    {
        return $rows
            ->skip(2)
            ->reject(fn (Collection $item): bool => empty(Arr::get($item, self::SPACE_KEY)))
            ->unique(self::SPACE_KEY)
            ->map(function (Collection $item): array {
                $spaceId = $this->fetchSpaceId($this->spaceData, Arr::get($item, self::SPACE_KEY));

                if (!empty($spaceId)) {
                    return compact('spaceId') + [
                        'dedicated' => $this->fetchNumber($item, self::DEDICATED_KEY),
                        'agreement' => $this->fetchNumber($item, self::AGREEMENT_KEY),
                        'indoor' => $this->fetchNumber($item, self::INDOOR_KEY),
                        'awning' => $this->fetchNumber($item, self::AWNING_KEY),
                        'balcony' => $this->fetchNumber($item, self::BALCONY_KEY),
                        'preservation' => $this->fetchNumber($item, self::PRESERVATION_KEY)
                    ];
                }

                return [];
            })
            ->filter()
            ->values();
    }

    /**
     * 取得土地面積匯入資料
     *
     * @param \Illuminate\Support\Collection $importData
     *
     * @return array
     */
    private function fetchLandAreaImportData(Collection $importData): array
    {
        $landArea = $this->spaceData->pluck('landArea')->filter();

        return $importData
            ->map(function (array $item) use ($landArea): array {
                $spaceId = (string) Arr::get($item, 'spaceId');

                $target = $landArea->where('space_id', $spaceId);
                $id = $target->first()?->id;

                return (new LandAreaData($item))
                    ->replace(compact('id'))
                    ->toColumnArray();
            })
            ->toArray();
    }

    /**
     * 取得專有面積匯入資料
     *
     * @param \Illuminate\Support\Collection $importData
     *
     * @return array
     */
    private function fetchExclusiveAreaImportData(Collection $importData): array
    {
        $exclusiveArea = $this->spaceData
            ->map(fn (CrmBuildingSpace $space): Collection => $space->exclusiveArea->whereIn('name', ExclusiveAreaName::names()))
            ->flatten(1);

        return $importData
            ->map(function (array $item) use ($exclusiveArea): array {
                $spaceId = (string) Arr::get($item, 'spaceId');
                $areaItem = Arr::only($item, ExclusiveAreaName::names());

                return Arr::map($areaItem, function (int $ping, string $name) use ($exclusiveArea, $spaceId): array {
                    $target = $exclusiveArea->whereStrict('space_id', $spaceId)->whereStrict('name', $name);
                    $id = $target->first()?->id;

                    return (new ExclusiveAreaData(compact('spaceId', 'name', 'ping')))
                        ->replace(compact('id'))
                        ->excludeColumn('allow_calculate')
                        ->toColumnArray();
                });
            })
            ->flatten(1)
            ->toArray();
    }

    /**
     * 取得約定專用面積設定匯入資料
     *
     * @param \Illuminate\Support\Collection $importData
     *
     * @return array
     */
    private function fetchAgreedDedicatedAreaSettingImportData(Collection $importData): array
    {
        $agreedDedicatedArea = $this->spaceData->pluck('agreedDedicatedAreaSetting')->filter();

        return $importData
            ->map(function (array $item) use ($agreedDedicatedArea): array {
                $spaceId = (string) Arr::get($item, 'spaceId');

                $target = $agreedDedicatedArea->where('space_id', $spaceId);
                $id = $target->first()?->id;

                return (new AgreedDedicatedAreaSettingData($item))
                    ->replace(compact('id'))
                    ->toColumnArray();
            })
            ->toArray();
    }
}
