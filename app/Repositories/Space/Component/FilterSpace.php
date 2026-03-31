<?php

declare(strict_types=1);

namespace App\Repositories\Space\Component;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

use App\Support\Enum\CrmHouseType;
use App\Support\Enum\LayoutSetting;
use App\Support\Enum\HouseRentState;
use App\Support\Enum\HouseLiveState;

use App\Support\Abstract\QueryFilter;

class FilterSpace extends QueryFilter
{
    use \App\Support\Trait\Repository\FilterTrait;

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function __construct(
        private Builder $query
    ) {}

    /**
     * 執行全部搜尋條件
     * @return FilterSpace
     */
    public function execute(): static
    {
        $reflection = new \ReflectionClass(__CLASS__);

        foreach ($reflection->getMethods() as $method) {
            if (
                $method->isPublic()
                &&
                __FILE__ === $method->getFileName()
                &&
                !in_array($method->name, ['__construct', __FUNCTION__])
                &&
                empty($method->getParameters())
            ) {
                $this->{$method->name}();
            }
        }

        return $this;
    }

    /**
     * 搜尋區名
     * @return FilterSpace
     */
    public function searchDistrictName(): static
    {
        $keyword = $this->fetchStringRequest('districtName');

        if (!empty($keyword)) {
            $this->query = $this->query->whereLike('district_name', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋棟別
     * @return FilterSpace
     */
    public function searchBuildingName(): static
    {
        $keyword = $this->fetchStringRequest('buildingName');

        if (!empty($keyword)) {
            $this->query = $this->query->whereLike('building_name', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋梯間
     * @return FilterSpace
     */
    public function searchStaircaseName(): static
    {
        $keyword = $this->fetchStringRequest('staircaseName');

        if (!empty($keyword)) {
            $this->query = $this->query->whereLike('staircase_name', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋樓層
     * @return FilterSpace
     */
    public function searchFloorName(): static
    {
        $keyword = $this->fetchStringRequest('floorName');

        if (!empty($keyword)) {
            $this->query = $this->query->whereLike('floor_name', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋戶別 / 公設名稱
     * @return FilterSpace
     */
    public function searchHouseholdName(): static
    {
        $keyword = $this->fetchStringRequest('householdName');

        if (!empty($keyword)) {
            $this->query = $this->query->whereLike('household_name', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋門牌
     * @return FilterSpace
     */
    public function searchDoorplate(): static
    {
        $keyword = $this->fetchStringRequest('doorplate');

        if (!empty($keyword)) {
            $this->query = $this->query->whereLike('doorplate', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋格局
     * @return FilterSpace
     */
    public function searchLayoutName(): static
    {
        $keyword = $this->fetchStringRequest('layoutName');

        if (!empty($keyword)) {
            $searchTarget = LayoutSetting::collect()
                ->filter(fn(string $name): bool => Str::contains($name, $keyword))
                ->keys();

            $this->query = $this->query
                ->where(function ($query) use ($keyword, $searchTarget) {
                    $query->whereRelation('layoutSetting', function (Builder $subQuery) use ($keyword) {
                        $subQuery->where('name', 'LIKE', "%{$keyword}%");
                    })
                        ->orWhereRelation('spaceLayout', function (Builder $subQuery) use ($keyword, $searchTarget) {
                            $subQuery->whereIn('type', $searchTarget)
                                ->orWhere('quantity', $keyword);
                        });
                });
        }

        return $this;
    }

    /**
     * 搜尋主要用途
     * @return FilterSpace
     */
    public function searchMainApplication(): static
    {
        $keyword = $this->fetchStringRequest('mainApplication');

        if (!empty($keyword)) {
            $searchTarget = CrmHouseType::collect()
                ->filter(fn(string $name): bool => Str::contains($name, $keyword))
                ->keys();

            $this->query = $this->query->whereIn('main_application', $searchTarget);
        }

        return $this;
    }

    /**
     * 搜尋居住情況
     * @return FilterSpace
     */
    public function searchLive(): static
    {
        $keyword = $this->fetchStringRequest('live');

        if (!empty($keyword)) {
            $searchTarget = HouseLiveState::collect()
                ->filter(fn(string $name): bool => Str::contains($name, $keyword))
                ->keys();

            $searchCondition = fn(Builder $searchQuery): Builder => $searchQuery->whereIn('live', $searchTarget);
            $this->query = $this->query->whereHas('houseState', $searchCondition);
        }

        return $this;
    }

    /**
     * 搜尋租售狀態
     * @return FilterSpace
     */
    public function searchRentalAndSale(): static
    {
        $keyword = $this->fetchStringRequest('rentalAndSale');

        if (!empty($keyword)) {
            $searchTarget = HouseRentState::collect()
                ->filter(fn(string $name): bool => Str::contains($name, $keyword))
                ->keys();

            $searchCondition = fn(Builder $searchQuery): Builder => $searchQuery->whereIn('rental_and_sale', $searchTarget);
            $this->query = $this->query->whereHas('houseState', $searchCondition);
        }

        return $this;
    }

    /**
     * 搜尋建議售價
     * @return FilterSpace
     */
    public function searchPrice(): static
    {
        $keyword = $this->fetchIntegerRequest('price');

        if (!empty($keyword)) {
            $this->query = $this->query->whereRelation('price', 'price', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋建議租金
     * @return FilterSpace
     */
    public function searchRentPrice(): static
    {
        $keyword = $this->fetchIntegerRequest('rentPrice');

        if (!empty($keyword)) {
            $this->query = $this->query->whereRelation('price', 'rent_price', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋能效標章
     * @return FilterSpace
     */
    public function searchCertification(): static
    {
        $keyName = 'certification';
        $keyword = $this->fetchIntegerRequest($keyName);

        if ($this->haveIntRequest($keyName)) {
            $this->query = match ($keyword) {
                0 => $this->query->doesntHave('certification'),
                1 => $this->query->has('certification'),
                default => $this->query
            };
        }

        return $this;
    }

    /**
     * 搜尋專有面積小計
     * @return FilterSpace
     */
    public function searchExclusiveAreaTotal(): static
    {
        $keyName = 'exclusiveAreaTotal';
        $keyword = $this->fetchIntegerRequest($keyName);

        if ($this->haveIntRequest($keyName)) {
            $this->query = $this->query
                ->whereRelation('exclusiveArea', function (Builder $query) use ($keyword): void {
                    $query
                        ->select('space_id')
                        ->groupBy('space_id')
                        ->havingRaw('SUM(ping) = ?', [$keyword]);
                });
        }

        return $this;
    }

    /**
     * 搜尋公設面積小計
     * @return FilterSpace
     */
    public function searchPublicHoldingAreaTotal(): static
    {
        $keyName = 'publicHoldingAreaTotal';
        $keyword = $this->fetchIntegerRequest($keyName);

        if ($this->haveIntRequest($keyName)) {
            $this->query = $this->query
                ->whereRelation('publicHoldingArea', function (Builder $query) use ($keyword): void {
                    $query
                        ->select('space_id')
                        ->groupBy('space_id')
                        ->havingRaw('SUM(total) = ?', [$keyword]);
                });
        }

        return $this;
    }

    /**
     * 搜尋登記面積
     * @return FilterSpace
     */
    public function searchRegisterTotal(): static
    {
        $keyName = 'registerArea';
        $keyword = $this->fetchIntegerRequest($keyName);

        if ($this->haveIntRequest($keyName)) {
            $tableName = $this->query->getModel()->getTable();

            $exclusiveAreaJoin = DB::table('exclusive_area')
                ->select('space_id', DB::raw('SUM(ping) AS exclusiveAreaTotal'))
                ->groupBy('space_id');

            $publicHoldingAreaJoin = DB::table('public_holding_area')
                ->select('space_id', DB::raw('SUM(total) AS publicHoldingAreaTotal'))
                ->groupBy('space_id');

            $this->query = $this->query
                ->leftJoinSub(
                    $exclusiveAreaJoin,
                    $exclusiveAreaJoin->from,
                    fn(JoinClause $join): JoinClause => $join->on("{$tableName}.space_id", "{$exclusiveAreaJoin->from}.space_id")
                )
                ->leftJoinSub(
                    $publicHoldingAreaJoin,
                    $publicHoldingAreaJoin->from,
                    fn(JoinClause $join): JoinClause => $join->on("{$tableName}.space_id", "{$publicHoldingAreaJoin->from}.space_id")
                )
                ->select(
                    "{$tableName}.*",
                    DB::raw('(IFNULL(exclusiveAreaTotal, 0) + IFNULL(publicHoldingAreaTotal, 0)) AS registerArea')
                )
                ->havingRaw('registerArea = ?', [$keyword]);
        }

        return $this;
    }

    /**
     * 搜尋約定面積小計
     * @return FilterSpace
     */
    public function searchAgreedDedicatedTotal(): static
    {
        $keyName = 'agreedDedicatedTotal';
        $keyword = $this->fetchIntegerRequest($keyName);

        if ($this->haveIntRequest($keyName)) {
            $this->query = $this->query
                ->whereRelation('agreedDedicatedArea', function (Builder $query) use ($keyword): void {
                    $query
                        ->select('space_id')
                        ->groupBy('space_id')
                        ->havingRaw('SUM(ping) = ?', [$keyword]);
                });
        }

        return $this;
    }

    /**
     * 搜尋土地面積
     * @return FilterSpace
     */
    public function searchLandArea(): static
    {
        $keyName = 'landArea';
        $keyword = $this->fetchIntegerRequest($keyName);

        if ($this->haveIntRequest($keyName)) {
            $this->query = $this->query
                ->whereRelation('landArea', function (Builder $query) use ($keyword): void {
                    $query->where('dedicated', $keyword);
                });
        }

        return $this;
    }

    /**
     * 搜尋約定土地面積
     * @return FilterSpace
     */
    public function searchLandAgreementArea(): static
    {
        $keyName = 'landAgreementArea';
        $keyword = $this->fetchIntegerRequest($keyName);

        if ($this->haveIntRequest($keyName)) {
            $this->query = $this->query
                ->whereRelation('landArea', function (Builder $query) use ($keyword): void {
                    $query->where('agreement', $keyword);
                });
        }

        return $this;
    }
}
