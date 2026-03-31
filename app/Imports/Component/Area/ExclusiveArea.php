<?php

namespace App\Imports\Component\Area;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\ToCollection;

use App\Models\CrmBuildingSpace;

use App\Support\Data\ExclusiveAreaData;

use App\Repositories\Excel\AreaImportRepository;

class ExclusiveArea implements ToCollection
{
    use \App\Support\Trait\Excel\Area\AreaTrait;

    /**
     * 戶別
     * @var int
     */
    public const SPACE_KEY = 0;

    /**
     * 面積名稱
     * @var int
     */
    public const AREA_NAME_KEY = 1;

    /**
     * 面積坪數
     * @var int
     */
    public const AREA_PING_KEY = 2;

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

        $exclusiveAreaImportData = $this->fetchExclusiveAreaImportData($importData);

        $areaImportRepository = new AreaImportRepository();
        $areaImportRepository->exclusiveAreaCreateOrUpdate($exclusiveAreaImportData);
    }

    /**
     * 取得匯入資料
     *
     * @param \Illuminate\Support\Collection $rows
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchImportData(Collection $rows): Collection
    {
        return $rows
            ->skip(1)
            ->reject(function (Collection $item): bool {
                return
                    empty(Arr::get($item, self::SPACE_KEY))
                    ||
                    empty(Arr::get($item, self::AREA_NAME_KEY));
            })
            ->map(function (Collection $item): array {
                $spaceId = $this->fetchSpaceId($this->spaceData, Arr::get($item, self::SPACE_KEY));

                if (!empty($spaceId)) {
                    return compact('spaceId') + [
                        'name' => $this->fetchString($item, self::AREA_NAME_KEY),
                        'ping' => $this->fetchNumber($item, self::AREA_PING_KEY)
                    ];
                }

                return [];
            })
            ->filter()
            ->values();
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
            ->map(fn (CrmBuildingSpace $space): Collection => $space->exclusiveArea)
            ->flatten(1);

        return $importData
            ->map(function (array $item) use ($exclusiveArea): array {
                $spaceId = (string) Arr::get($item, 'spaceId');
                $areaName = (string) Arr::get($item, 'name');

                $target = $exclusiveArea->whereStrict('space_id', $spaceId)->whereStrict('name', $areaName);
                $id = $target->first()?->id;

                return (new ExclusiveAreaData($item))
                    ->replace(compact('id'))
                    ->excludeColumn('allow_calculate')
                    ->toColumnArray();
            })
            ->toArray();
    }
}
