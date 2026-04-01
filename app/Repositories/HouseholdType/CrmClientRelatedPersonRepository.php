<?php

declare(strict_types=1);

namespace App\Repositories\HouseholdType;

use Illuminate\Support\Collection;
use App\Models\CrmClientRelatedPerson;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmClientRelatedPersonRepository
{
    /**
     * @param $data
     *
     * @return bool
     */
    public function insert($data): bool
    {
        return CrmClientRelatedPerson::insert($data);
    }

    /**
     * @param  array  $clientId
     *
     * @return int
     */
    public function forceDelete(array $clientIds): int
    {
        return CrmClientRelatedPerson::whereIn('related_client_id', $clientIds)
            ->forceDelete();
    }

    /**
     * @param  int  $propertyInfoId
     *
     * @return int
     */
    public function forceDeleteOfInfoId(int $propertyInfoId): int
    {
        return CrmClientRelatedPerson::where('property_info_id', $propertyInfoId)
            ->forceDelete();
    }

    /**
     * @param  array  $clientId
     *
     * @return int
     */
    public function forceDeleteByClientId(array $clientIds): int
    {
        return CrmClientRelatedPerson::whereIn('client_id', $clientIds)
            ->forceDelete();
    }

    /**
     * @param  string  $spaceId
     * @param  array  $clientIds
     * @param  int  $id
     *
     * @return Collection
     */
    public function find(string $spaceId, array $clientIds, int $id): Collection
    {
        return CrmClientRelatedPerson::where('space_id', $spaceId)
            ->whereIn('client_id', $clientIds)
            ->where('property_info_id', $id)
            ->with([
                'relatedClient' => fn (Builder|BelongsTo $query): Builder|BelongsTo =>
                $query->where('company_id', crm('company_id')),
                'relatedClient.crmClientContact'
            ])
            ->get();
    }
}