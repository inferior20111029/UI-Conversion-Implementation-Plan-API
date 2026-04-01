<?php

declare(strict_types=1);

namespace App\Repositories\RentalSaleAmounts;

use App\Models\SaleAveragePriceCalculate;

class SaleAveragePriceCalculateRepository
{
    /**
     * @return SaleAveragePriceCalculate |null
     */
    public function find(): ?SaleAveragePriceCalculate 
    {
        return SaleAveragePriceCalculate::where('company_id', crm('company_id'))
              ->where('comid', crm('community_id'))
              ->first();
    }

    /**
     * @param  array  $data
     *
     * @return bool
     */
    public function insert(array $data): bool
    {
        return SaleAveragePriceCalculate::insert($data);
    }

    /**
     * @param $id
     *
     * @return int
     */
    public function forceDelete($id): int
    {
        return SaleAveragePriceCalculate::where('sale_average_price_id', $id)
            ->forceDelete();
    }
}