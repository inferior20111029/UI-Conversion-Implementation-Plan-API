<?php

declare(strict_types=1);

namespace App\Repositories\Space;

use Illuminate\Support\Collection;

use App\Models\CrmLayoutSetting;
use App\Models\CrmLayoutSettingDetail;

class CrmLayoutSettingRepository
{
    use \App\Support\Trait\Paginate\PaginateTrait;

    /**
     * @param  string|int  $id
     *
     * @return CrmLayoutSetting|null
     */
    public function findById(string|int $id): ?CrmLayoutSetting
    {
        return CrmLayoutSetting::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('id', $id)
            ->with('crmLayoutSettingDetail')
            ->first();
    }

    /**
     * @return Collection|null
     */
    public function UnitPriceOption(): ?Collection
    {
        return CrmLayoutSetting::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->get();
    }

    /**
     * @param  string|int  $id
     * @param  array       $updateData
     *
     * @return int
     */
    public function update(string|int $id, array $updateData): int
    {
        return CrmLayoutSetting::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('id', $id)
            ->update($updateData);
    }

    /**
     * 建立單筆格局設定
     *
     * @param array $insertData
     *
     * @return CrmLayoutSetting|null
     */
    public function insert(array $insertData): ?CrmLayoutSetting
    {
        return CrmLayoutSetting::create($insertData);
    }

    /**
     *
     * @param array $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return CrmLayoutSetting::destroy($ids);
    }

    /**
     * @param  string|null  $name
     * @param  int          $page
     * @param  int          $perPage
     *
     * @return mixed
     */
    public function layoutSettingPage(string|null $name, bool $pageLess = false)
    {
        $query = CrmLayoutSetting::whereCompanyId(crm('company_id'))
            ->where('comid', crm('community_id'))
            ->when(isset($name), fn ($query) => $query->whereLike('name', (string) $name));

        return $pageLess ? $query->get() : $query->paginate($this->paginateLimit());
    }
}
