<?php

namespace App\Imports\Component\Area;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\ToCollection;

use App\Models\CrmBuildingSpace;

use App\Support\Data\AgreedDedicatedAreaData;

use App\Repositories\Excel\AreaImportRepository;

class AgreedDedicatedArea implements ToCollection
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
     * 面積大小
     * @var int
     */
    public const PING_KEY = 2;

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

        $agreedDedicatedArea = $this->fetchAgreedDedicatedAreaImportData($importData);

        $areaImportRepository = new AreaImportRepository();
        $areaImportRepository->agreedDedicatedAreaCreateOrUpdate($agreedDedicatedArea);
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
                        'ping' => $this->fetchNumber($item, self::PING_KEY)
                    ];
                }

                return [];
            })
            ->filter()
            ->values();
    }

    /**
     * 取得約定專用面積匯入資料
     *
     * @param \Illuminate\Support\Collection $importData
     *
     * @return array
     */
    private function fetchAgreedDedicatedAreaImportData(Collection $importData): array
    {
        $agreedDedicatedArea = $this->spaceData
            ->map(fn (CrmBuildingSpace $space): Collection => $space->agreedDedicatedArea)
            ->flatten(1);

        return $importData
            ->map(function (array $item) use ($agreedDedicatedArea): array {
                $spaceId = (string) Arr::get($item, 'spaceId');
                $name = (string) Arr::get($item, 'name');

                $target = $agreedDedicatedArea
                    ->whereStrict('space_id', $spaceId)
                    ->whereStrict('name', $name);

                $id = $target->first()?->id;

                return (new AgreedDedicatedAreaData($item))
                    ->replace(compact('id'))
                    ->toColumnArray();
            })
            ->toArray();
    }
}
