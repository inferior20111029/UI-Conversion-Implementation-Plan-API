<?php

declare(strict_types=1);

namespace App\Repositories\HouseholdType;

use Illuminate\Support\Collection;

use App\Models\CrmClientHasCompany;

class CrmClientHasCompanyRepository
{
    /**
     * @param array $data
     * @return int
     */
    public function insertGetId($data): int
    {
        return CrmClientHasCompany::insertGetId($data);
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function insert($data): bool
    {
        return CrmClientHasCompany::insert($data);
    }

    /**
     * @param  array  $clientId
     *
     * @return int
     */
    public function forceDelete(array $clientIds): int
    {
        return CrmClientHasCompany::whereIn('client_id', $clientIds)
            ->forceDelete();
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        return CrmClientHasCompany::upsert($data, ['id']);
    }
}
