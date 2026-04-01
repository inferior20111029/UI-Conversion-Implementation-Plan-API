<?php

declare(strict_types=1);

namespace App\Repositories\Space;

use Illuminate\Support\Collection;

use App\Models\CrmSpaceConfiguration;

class CrmLanguageConfigurationRepository
{
    /**
     * 取得空間組態資料
     *
     * @return \Illuminate\Support\Collection
     */
    public function findAll(array $data): Collection
    {
        return CrmSpaceConfiguration::where($data)
            ->orderBy('configuration_natsort')
            ->get();
    }

    /**
     * 取得售價平均差空間結果
     *
     * @return \Illuminate\Support\Collection
     */
    public function findCalculate(array $data): Collection
    {
        return CrmSpaceConfiguration::where($data)
            ->whereIn('configuration_type', ['building', 'floor'])
            ->orderBy('configuration_natsort')
            ->get();
    }

    /**
     * 取得空間組態資料
     *
     * @param string $type
     * @param string $languageId
     * @return \Illuminate\Support\Collection
     */
    public function findById(string $type, string $languageId): Collection
    {
        return CrmSpaceConfiguration::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('configuration_type', $type)
            ->where('language_id', $languageId)
            ->get();
    }

    /**
     * @param string $type
     * @return Collection
     */
    public function findByType(string $type): Collection
    {
        return CrmSpaceConfiguration::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('configuration_type', $type)
            ->get();
    }

    /**
     * @return Collection
     */
    public function find(): Collection
    {
        return CrmSpaceConfiguration::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->get();
    }

    /**
     * @param array $updateData
     * @return int
     */
    public function upsert(array $updateData): int
    {
        return CrmSpaceConfiguration::upsert($updateData, ['configuration_id']);
    }

    /**
     * @param array $updateData
     * @return int
     */
    public function update(array $updateData): int
    {
        return CrmSpaceConfiguration::where([
            'configuration_type' => $updateData['configuration_type'],
            'language_id'        => $updateData['language_id'],
        ])->update([
            'language' => $updateData['language'],
        ]);
    }

    /**
     * @param array $data
     * @return int
     */
    public function insert(array $data): bool
    {
        return CrmSpaceConfiguration::insert($data);
    }

    /**
     *
     * @param array $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return CrmSpaceConfiguration::destroy($ids);
    }

    /**
     * @param  int  $languageId
     * @param  string  $type
     *
     * @return int
     */
    public function destroyLimit(int $languageId, string $type,int $limit): int
    {
        return CrmSpaceConfiguration::where('language_id', $languageId)
            ->where('configuration_type', $type)
            ->orderByRaw('LENGTH(configuration_value) DESC, configuration_value DESC')
            ->limit($limit)
            ->forceDelete();
    }

    /**
     * Delete records based on the provided conditions.
     *
     * @param array $destroyData
     * @return int
     */
    public function destroyById(array $destroyData): int
    {
        return CrmSpaceConfiguration::where($destroyData)->delete();
    }

    /**
     * @param  int  $companyId
     * @param  int  $communityId
     * @param  string  $configurationValue
     * @param  string  $configurationType
     *
     * @return int
     */
    public function forceDelete(int $companyId, int $communityId, string $configurationValue, string $configurationType): int
    {
        return CrmSpaceConfiguration::where('company_id', $companyId)
            ->where('comid', $communityId)
            ->where('configuration_type', $configurationType)
            ->where('configuration_value', $configurationValue)
            ->forceDelete();
    }

    /**
     * @param array $data
     * @return int
     */
    public function count(array $data): int
    {
        return CrmSpaceConfiguration::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('language_id', $data['language_id'])
            ->where('configuration_type', $data['configuration_type'])
            ->count();
    }
}
