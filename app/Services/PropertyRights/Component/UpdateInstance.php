<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

use Illuminate\Http\Request;

use App\Models\CrmBuildingSpace;

final class UpdateInstance
{
    /**
     * @param CrmBuildingSpace $spaceData 戶別資料
     * @param Request $request Request
     */
    public function __construct(
        public readonly CrmBuildingSpace $spaceData,
        public readonly Request $request
    ) {
        $request->merge([
            'spaceId' => $spaceData->space_id
        ]);
    }

    /**
     * 更新戶別資料
     *
     * @return void
     */
    public function space(): void
    {
        (new UpdateSpace())->execute($this);
    }

    /**
     * 更新戶別文件
     *
     * @return void
     */
    public function document(): void
    {
        (new UpdateDocument())->execute($this);
    }

    /**
     * 更新戶別價格
     *
     * @return void
     */
    public function price(): void
    {
        (new UpdatePrice())->execute($this);
    }

    /**
     * 更新戶別規劃型態
     *
     * @return void
     */
    public function planning(): void
    {
        (new UpdatePlanning())->execute($this);
    }

    /**
     * 更新格局設定
     *
     * @return void
     */
    public function layout(): void
    {
        (new UpdateLayout())->execute($this);
    }

    /**
     * 更新房屋概況
     *
     * @return void
     */
    public function state(): void
    {
        (new UpdateState())->execute($this);
    }

    /**
     * 更新面積設定
     *
     * @return void
     */
    public function areaSetting(): void
    {
        (new UpdateAreaSetting())->execute($this);
    }

    /**
     * 更新土地面積
     *
     * @return void
     */
    public function landArea(): void
    {
        (new UpdateLandArea())->execute($this);
    }

    /**
     * 更新專有面積
     *
     * @return void
     */
    public function exclusiveArea(): void
    {
        (new UpdateExclusiveArea())->execute($this);
    }

    /**
     * 更新公設持分面積
     *
     * @return void
     */
    public function publicHoldingArea(): void
    {
        (new UpdatePublicHoldingArea())->execute($this);
    }

    /**
     * 更新約定專用面積設定
     *
     * @return void
     */
    public function agreedDedicatedAreaSetting(): void
    {
        (new UpdateAgreedDedicatedAreaSetting())->execute($this);
    }

    /**
     * 更新約定專用面積-項目
     *
     * @return void
     */
    public function agreedDedicatedArea(): void
    {
        (new UpdateAgreedDedicatedArea())->execute($this);
    }

    /**
     * 更新戶別車位
     *
     * @return void
     */
    public function carParking(): void
    {
        (new UpdateCarParking())->execute($this);
    }

    /**
     * 更新斡旋金
     *
     * @return void
     */
    public function earnestPayment(): void
    {
        (new UpdateEarnestPayment())->execute($this);
    }

    /**
     * 取得戶別 ID
     *
     * @return string
     */
    public function fetchSpaceId(): string
    {
        return (string) $this?->spaceData?->space_id;
    }
}
