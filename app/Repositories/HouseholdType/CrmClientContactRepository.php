<?php

declare(strict_types=1);

namespace App\Repositories\HouseholdType;

use App\Models\CrmClientContact;

class CrmClientContactRepository
{
    /**
     * @param array $data
     * @return int
     */
    public function insertGetId($data): int
    {
        return CrmClientContact::insertGetId($data);
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function insert($data): bool
    {
        return CrmClientContact::insert($data);
    }

    /**
     * @param  array  $clientId
     *
     * @return int
     */
    public function forceDelete(array $clientIds): int
    {
        return CrmClientContact::whereIn('client_id', $clientIds)
            ->forceDelete();
    }

}
