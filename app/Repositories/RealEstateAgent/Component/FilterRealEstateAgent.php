<?php

declare(strict_types=1);

namespace App\Repositories\RealEstateAgent\Component;

use Illuminate\Database\Eloquent\Builder;

use App\Support\Abstract\QueryFilter;

class FilterRealEstateAgent extends QueryFilter
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
     * @return FilterRealEstateAgent
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
     * 搜尋帳號
     *
     * @return FilterRealEstateAgent
     */
    public function searchAccount(): static
    {
        $keyword = $this->fetchStringRequest('account');

        if (!empty($keyword)) {
            $this->whereLikeRealEstateAgentLogin('account', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋名字
     *
     * @return FilterRealEstateAgent
     */
    public function searchName(): static
    {
        $keyword = $this->fetchStringRequest('name');

        if (!empty($keyword)) {
            $this->whereLikeRealEstateAgent('name', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋公司名稱
     *
     * @return FilterRealEstateAgent
     */
    public function searchCompanyName(): static
    {
        $keyword = $this->fetchStringRequest('companyName');

        if (!empty($keyword)) {
            $this->whereLikeRealEstateAgent('company_name', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋手機號碼
     *
     * @return FilterRealEstateAgent
     */
    public function searchCellPhone(): static
    {
        $keyword = $this->fetchStringRequest('cellphone');

        if (!empty($keyword)) {
            $this->whereLikeRealEstateAgent('cellphone', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋聯絡電話
     *
     * @return FilterRealEstateAgent
     */
    public function searchContactNumbers(): static
    {
        $keyword = $this->fetchStringRequest('contactNumbers');

        if (!empty($keyword)) {
            $this->whereLikeRealEstateAgent('contact_numbers', $keyword);
        }

        return $this;
    }

    /**
     * 搜尋 Email
     *
     * @return FilterRealEstateAgent
     */
    public function searchEmail(): static
    {
        $keyword = $this->fetchStringRequest('email');

        if (!empty($keyword)) {
            $this->whereLikeRealEstateAgent('email', $keyword);
        }

        return $this;
    }

    /**
     * 模糊搜尋仲介人資料
     *
     * @param string $columnName
     * @param int|string $keyword
     *
     * @return void
     */
    private function whereLikeRealEstateAgent(string $columnName, int|string $keyword): void
    {
        $this->query = $this->query->whereRelation('realEstateAgent', $columnName, 'LIKE', "%{$keyword}%");
    }

    /**
     * 模糊搜尋仲介人登入資料
     *
     * @param string $columnName
     * @param int|string $keyword
     *
     * @return void
     */
    private function whereLikeRealEstateAgentLogin(string $columnName, int|string $keyword): void
    {
        $this->query = $this->query->whereRelation('realEstateAgent.login', $columnName, 'LIKE', "%{$keyword}%");
    }
}
