<?php

declare(strict_types=1);

namespace App\Repositories\PropertyManage\Component;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

use App\Support\Abstract\QueryFilter;

use App\Support\Enum\HousePlanning;

class FilterProperty extends QueryFilter
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
     * @return static
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
     * 搜尋房租價格
     * @return static
     */
    public function searchRentFees(): static
    {
        $keyword = $this->fetchIntegerRequest('searchRentFees');

        if (!empty($keyword)) {
            $this->query = $this->query->whereRelation('fees', 'price', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋管理費
     * @return static
     */
    public function searchManagementFee(): static
    {
        $keyword = $this->fetchIntegerRequest('searchManagementFee');

        if (!empty($keyword)) {
            $this->query = $this->query->whereRelation('fees', 'management_fee', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋房屋型態
     * @return static
     */
    public function searchSpacePlanType(): static
    {
        $keyword = $this->fetchArrayRequest('searchSpacePlanType');

        if (!empty($keyword)) {
            $searchTarget = HousePlanning::collect()
                ->filter(fn(string $name): bool => Str::contains($name, $keyword))
                ->keys();

            $this->query = $this->query
                ->whereHas('crmBuildingSpace.planning', function (Builder $query) use ($searchTarget): void {
                    $query->whereIn('planning', $searchTarget);
                });
        }

        return $this;
    }

    /**
     * 搜尋格局
     * @return static
     */
    public function searchLayout(): static
    {
        $keyword = $this->fetchArrayRequest('searchLayout', dataType: 'int');

        if (!empty($keyword)) {
            $this->query = $this->query
                ->where(function (Builder $query) use ($keyword): void {
                    $query
                        ->whereHas(
                            'crmBuildingSpace.layoutSetting.crmLayoutSettingDetail',
                            function (Builder $layoutQuery) use ($keyword): void {
                                $layoutQuery
                                    ->select('layout_setting_id', DB::raw('SUM(quantity) AS totalQuantity'))
                                    ->groupBy('layout_setting_id');

                                $this->havingSpaceLayout($layoutQuery, $keyword);
                            }
                        )
                        ->orWhereHas(
                            'crmBuildingSpace.spaceLayout',
                            function (Builder $layoutQuery) use ($keyword): void {
                                $layoutQuery
                                    ->select('space_id', DB::raw('SUM(quantity) AS totalQuantity'))
                                    ->groupBy('space_id');

                                $this->havingSpaceLayout($layoutQuery, $keyword);
                            }
                        );
                });
        }

        return $this;
    }

    /**
     * 搜尋登記面積
     * @return FilterProperty
     */
    public function searchRegisterArea(): static
    {
        $keyword = $this->fetchArrayRequest('searchRegisterArea');

        if (!empty($keyword)) {
            $tableName = $this->query->getModel()->getTable();

            $exclusiveAreaJoin = DB::table('exclusive_area')
                ->select('space_id', DB::raw('SUM(ping) AS exclusiveAreaTotal'))
                ->groupBy('space_id');

            $publicHoldingAreaJoin = DB::table('public_holding_area')
                ->select('space_id', DB::raw('SUM(total) AS publicHoldingAreaTotal'))
                ->groupBy('space_id');

            $mainQuery = $this->query
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
                );

            foreach ($keyword as $value) {
                $between = explode('-', $value);

                if (count($between) !== 2) {
                    continue;
                }

                $min = (int) Arr::get($between, 0);
                $max = (int) Arr::get($between, 1);

                $mainQuery->orHavingRaw('registerArea BETWEEN ? AND ?', [$min, $max]);
            }

            $this->query = $mainQuery;
        }

        return $this;
    }

    /**
     * 比較格局篩選
     *
     * @param \Illuminate\Database\Eloquent\Builder $layoutQuery
     * @param array $keyword
     *
     * @return void
     */
    private function havingSpaceLayout(Builder $layoutQuery, array $keyword): void
    {
        $layoutMaxLimit = 5;

        foreach ($keyword as $value) {
            if ($value >= $layoutMaxLimit) {
                /**
                 * 如果為 5 或是大於 5 視為搜尋格局以上
                 */
                $layoutQuery->orHavingRaw('totalQuantity >= ?', [$layoutMaxLimit]);
                continue;
            }

            $layoutQuery->orHavingRaw('totalQuantity = ?', [$value]);
        }
    }
}
