<?php

declare(strict_types=1);

namespace App\Repositories\VisitReserve\Component;

use Illuminate\Database\Eloquent\Builder;

use App\Support\Abstract\QueryFilter;

use App\Models\Login;
use App\Models\RealEstateAgent;
use App\Models\Login\LoginUser;

class FilterVisitReserve extends QueryFilter
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
     *
     * @return FilterVisitReserve
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
     * @return FilterVisitReserve
     */
    public function searchDistrictName(): static
    {
        $keyword = $this->fetchStringRequest('searchDistrictName');

        if (!empty($keyword)) {
            $this->whereLikeSpaceRelation('district_name', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋棟別
     * @return FilterVisitReserve
     */
    public function searchBuildingName(): static
    {
        $keyword = $this->fetchStringRequest('searchBuildingName');

        if (!empty($keyword)) {
            $this->whereLikeSpaceRelation('building_name', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋梯間
     * @return FilterVisitReserve
     */
    public function searchStaircaseName(): static
    {
        $keyword = $this->fetchStringRequest('searchStaircaseName');

        if (!empty($keyword)) {
            $this->whereLikeSpaceRelation('staircase_name', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋樓層
     * @return FilterVisitReserve
     */
    public function searchFloorName(): static
    {
        $keyword = $this->fetchStringRequest('searchFloorName');

        if (!empty($keyword)) {
            $this->whereLikeSpaceRelation('floor_name', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋戶別 / 公設名稱
     * @return FilterVisitReserve
     */
    public function searchHouseholdName(): static
    {
        $keyword = $this->fetchStringRequest('searchHouseholdName');

        if (!empty($keyword)) {
            $this->whereLikeSpaceRelation('household_name', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋門牌
     * @return FilterVisitReserve
     */
    public function searchDoorplate(): static
    {
        $keyword = $this->fetchStringRequest('searchDoorplate');

        if (!empty($keyword)) {
            $this->whereLikeSpaceRelation('doorplate', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋帶看人員
     * @return FilterVisitReserve
     */
    public function searchRealEstateAgentName(): static
    {
        $keyword = $this->fetchStringRequest('searchRealEstateAgentName');

        if (!empty($keyword)) {
            $this->query = $this->query->whereRelation('realEstateAgent', 'name', 'LIKE', "%{$keyword}%");
        }

        return $this;
    }


    /**
     * 搜尋預約者
     * @return FilterVisitReserve
     */
    public function searchReservationPerson(): static
    {
        $keyword = $this->fetchStringRequest('searchReservationPerson');

        if (!empty($keyword)) {
            $this->query = $this->query
                ->whereHasMorph(
                    'visitReserveTable',
                    [Login::class, LoginUser::class],
                    function (Builder $query, string $type) use ($keyword): Builder {
                        return match ($type) {
                            LoginUser::class => $query->whereLike('username', $keyword),
                            Login::class => $query
                                ->whereHasMorph(
                                    'loginRealEstateAgent',
                                    [RealEstateAgent::class],
                                    fn(Builder $query): Builder => $query->whereLike('name', $keyword)
                                ),
                            default => $query
                        };
                    }
                );
        }

        return $this;
    }

    /**
     * 搜尋訪客
     * @return FilterVisitReserve
     */
    public function searchVisitorsName(): static
    {
        $keyword = $this->fetchStringRequest('searchVisitorsName');

        if (!empty($keyword)) {
            $this->query = $this->query->whereLike('visitors_name', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋預約時間
     * @return FilterVisitReserve
     */
    public function searchAppointmentTime(): static
    {
        $keyword = $this->fetchStringRequest('searchAppointmentTime');

        if (!empty($keyword)) {
            $this->query = $this->query->whereLike('appointment_time', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋抵達時間
     * @return FilterVisitReserve
     */
    public function searchArrivalTime(): static
    {
        $keyword = $this->fetchStringRequest('searchArrivalTime');

        if (!empty($keyword)) {
            $this->query = $this->query->whereLike('arrival_time', $keyword);
        }

        return $this;
    }

    /**
     * 模糊搜尋建案空間
     *
     * @param string $columnName
     * @param int|string $keyword
     *
     * @return void
     */
    private function whereLikeSpaceRelation(string $columnName, int|string $keyword): void
    {
        $this->query = $this->query->whereRelation('property.crmBuildingSpace', $columnName, 'LIKE', "%{$keyword}%");
    }
}
