<?php

declare(strict_types=1);

namespace App\Repositories\Space;

use Illuminate\Support\Collection;

use App\Models\CrmLanguageSystem;

class CrmLanguageSystemRepository
{
    /**
     * 取得空間組態資料
     *
     * @return \Illuminate\Support\Collection
     */
    public function findByType(string $type): Collection
    {
        return CrmLanguageSystem::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('space_type', $type)
            ->get('language_id');
    }

    /**
     * @param array $data
     * @return Collection
     */
    public function findAll(array $data): Collection
    {
        return CrmLanguageSystem::where($data)->get();
    }

    /**
     * @param array $updateData
     * @return int
     */
    public function updateOrCreate(array $updateData): CrmLanguageSystem
    {
        return CrmLanguageSystem::updateOrCreate(
            [
                'company_id' => $updateData['company_id'],
                'comid'      => $updateData['comid'],
                'space_type' => $updateData['space_type'],
            ],
            [
                'language_id' => $updateData['language_id'],
            ]
        );
    }


    /**
     * Delete records based on the provided conditions.
     *
     * @param array $destroyData
     * @return int
     */
    public function delete(array $destroyData): int
    {
        return CrmLanguageSystem::where($destroyData)->delete();
    }
}
