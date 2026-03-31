<?php

namespace App\Imports\Component\Area;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\ToCollection;

use App\Models\CrmBuildingSpace;

use App\Support\Data\PublicHoldingAreaData;

use App\Repositories\Excel\AreaImportRepository;

class PublicHoldingArea implements ToCollection
{
    use \App\Support\Trait\Excel\Area\AreaTrait;

    /**
     * 戶別
     * @var int
     */
    public const SPACE_KEY = 0;

    /**
     * 建號
     * @var int
     */
    public const CONSTRUCTION_NUMBER_KEY = 1;

    /**
     * 共有總面積
     * @var int
     */
    public const TOTAL_AREA_KEY = 2;

    /**
     * 權利範圍-分母
     * @var int
     */
    public const OWNERSHIP_DENOMINATOR_KEY = 3;

    /**
     * 權利範圍-分子
     * @var int
     */
    public const OWNERSHIP_MOLECULAR_KEY = 4;

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

        $publicHoldingAreaImportData = $this->fetchPublicHoldingAreaImportData($importData);

        $areaImportRepository = new AreaImportRepository();
        $areaImportRepository->publicHoldingAreaCreateOrUpdate($publicHoldingAreaImportData);
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
                    empty(Arr::get($item, self::CONSTRUCTION_NUMBER_KEY));
            })
            ->map(function (Collection $item): array {
                $spaceId = $this->fetchSpaceId($this->spaceData, Arr::get($item, self::SPACE_KEY));

                if (!empty($spaceId)) {
                    return compact('spaceId') + [
                        'constructionNumber' => $this->fetchString($item, self::CONSTRUCTION_NUMBER_KEY),
                        'total' => $this->fetchNumber($item, self::CONSTRUCTION_NUMBER_KEY),
                        'ownershipDenominator' => $this->fetchNumber($item, self::OWNERSHIP_DENOMINATOR_KEY),
                        'ownershipMolecular' => $this->fetchNumber($item, self::OWNERSHIP_MOLECULAR_KEY)
                    ];
                }

                return [];
            })
            ->filter()
            ->values();
    }

    /**
     * 取得公設持分面積匯入資料
     *
     * @param \Illuminate\Support\Collection $importData
     *
     * @return array
     */
    private function fetchPublicHoldingAreaImportData(Collection $importData): array
    {
        $publicHoldingArea = $this->spaceData
            ->map(fn (CrmBuildingSpace $space): Collection => $space->publicHoldingArea)
            ->flatten(1);

        return $importData
            ->map(function (array $item) use ($publicHoldingArea): array {
                $spaceId = (string) Arr::get($item, 'spaceId');
                $constructionNumber = (string) Arr::get($item, 'constructionNumber');

                $target = $publicHoldingArea
                    ->whereStrict('space_id', $spaceId)
                    ->whereStrict('construction_number', $constructionNumber);

                $id = $target->first()?->id;

                return (new PublicHoldingAreaData($item))
                    ->replace(compact('id'))
                    ->toColumnArray();
            })
            ->toArray();
    }
}
