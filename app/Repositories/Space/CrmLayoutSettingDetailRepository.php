<?php

declare(strict_types=1);

namespace App\Repositories\Space;

use Illuminate\Support\Collection;

use App\Models\CrmLayoutSettingDetail;

class CrmLayoutSettingDetailRepository
{
    /**
     * 建立單筆格局設定
     *
     * @param  array  $insertData
     *
     * @return bool
     */
    public function insert(array $insertData): bool
    {
        return CrmLayoutSettingDetail::insert($insertData);
    }

    /**
     * @param  string|int  $layoutSettingId
     *
     * @return int
     */
    public function forceDelete(string|int $layoutSettingId): int
    {
        return CrmLayoutSettingDetail::where('layout_setting_id', $layoutSettingId)
            ->forceDelete();
    }
}
