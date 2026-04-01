<?php

namespace App\Support\Trait\Excel\Area;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Number;
use Illuminate\Support\Collection;

use App\Models\CrmBuildingSpace;

use App\Exports\AreaTemplate;

trait AreaTrait
{
    /**
     * 取得戶別 ID
     *
     * @param Collection $spaceData 戶別資料
     * @param string $importSpace
     *
     * @return string|null
     */
    public function fetchSpaceId(Collection $spaceData, string $importSpace): ?string
    {
        $importSpaceSplit = explode('-', $importSpace);

        $spaceUuidStart = (string) Arr::get($importSpaceSplit, 0);

        $buildingName = (string) Arr::get($importSpaceSplit, 1);
        if (AreaTemplate::DEFAULT_BUILDING_NAME === $buildingName) {
            $buildingName = null;
        }

        $floorName = (string) Arr::get($importSpaceSplit, 2);
        if (AreaTemplate::DEFAULT_FLOOR_NAME === $floorName) {
            $floorName = null;
        }

        $householdName = (string) Arr::get($importSpaceSplit, 3);

        return $spaceData
            ->filter(fn (CrmBuildingSpace $space): bool => Str::contains($space->space_id, $spaceUuidStart))
            ->where('building_name', $buildingName)
            ->where('floor_name', $floorName)
            ->where('household_name', $householdName)
            ->value('space_id');
    }

    /**
     * 取得限制最大字元的文字
     *
     * @param array|\Illuminate\Support\Collection $item
     * @param int $dataKey
     *
     * @return string
     */
    public function fetchString(array|Collection $item, int $dataKey): string
    {
        $string = (string) Arr::get($item, $dataKey);
        return str()->of($string)->take(255)->toString();
    }

    /**
     * 取得限制最大數字
     *
     * @param array|\Illuminate\Support\Collection $item
     * @param int $dataKey
     *
     * @return int
     */
    public function fetchNumber(array|Collection $item, int $dataKey): int
    {
        $number = (int) Arr::get($item, $dataKey);
        return Number::clamp($number, min: 0, max: 15000000);
    }
}
