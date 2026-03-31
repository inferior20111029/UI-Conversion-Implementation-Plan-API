<?php

declare(strict_types=1);

namespace App\Repositories\RentalSaleAmounts;

use App\Models\SaleAveragePrice;

class SaleAveragePriceRepository
{
    /**
     * @return SaleAveragePrice|null
     */
    public function find(): ?SaleAveragePrice
    {
        return SaleAveragePrice::where('company_id', crm('company_id'))
              ->where('comid', crm('community_id'))
              ->with('calculate')
              ->first();
    }

    /**
     * @param  array  $data
     *
     * @return SaleAveragePrice|null
     */
    public function createOrUpdate(array $data): ?SaleAveragePrice
    {
       return SaleAveragePrice::updateOrCreate(
            [
                'company_id' => $data['company_id'],
                'comid'      => $data['comid'],
            ],
            $data
        );
    }

    /**
     *
     * @param array $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return SaleAveragePrice::destroy($ids);
    }
}