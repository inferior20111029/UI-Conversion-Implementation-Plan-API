<?php

declare(strict_types=1);

namespace App\Repositories\Excel;

use App\Models\LandArea;
use App\Models\ExclusiveArea;
use App\Models\PublicHoldingArea;
use App\Models\AgreedDedicatedArea;
use App\Models\AgreedDedicatedAreaSetting;

class AreaImportRepository
{
    /**
     * 建立或更新土地面積
     *
     * @param array $importData 匯入資料
     *
     * @return int
     */
    public function landAreaCreateOrUpdate(array $importData): int
    {
        return LandArea::upsert($importData, ['id']);
    }

    /**
     * 建立或更新專有面積
     *
     * @param array $importData 匯入資料
     *
     * @return int
     */
    public function exclusiveAreaCreateOrUpdate(array $importData): int
    {
        return ExclusiveArea::upsert($importData, ['id']);
    }

    /**
     * 建立或更新公設持分面積
     *
     * @param array $importData 匯入資料
     *
     * @return int
     */
    public function publicHoldingAreaCreateOrUpdate(array $importData): int
    {
        return PublicHoldingArea::upsert($importData, ['id']);
    }

    /**
     * 建立或更新約定專用面積設定
     *
     * @param array $importData 匯入資料
     *
     * @return int
     */
    public function agreedDedicatedAreaCreateOrUpdate(array $importData): int
    {
        return AgreedDedicatedArea::upsert($importData, ['id']);
    }

    /**
     * 建立或更新約定專用面積設定
     *
     * @param array $importData 匯入資料
     *
     * @return int
     */
    public function agreedDedicatedAreaSettingCreateOrUpdate(array $importData): int
    {
        return AgreedDedicatedAreaSetting::upsert($importData, ['id']);
    }
}
