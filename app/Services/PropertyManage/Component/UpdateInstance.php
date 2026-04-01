<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Component;

use Illuminate\Http\Request;

use App\Models\Property;

final class UpdateInstance
{
    /**
     * @param \App\Models\Property $propertyData 物件資料
     * @param \Illuminate\Http\Request $request Request
     */
    public function __construct(
        public readonly Property $propertyData,
        public readonly Request $request
    ) {}

    /**
     * 更新物件資料
     *
     * @return void
     */
    public function property(): void
    {
        (new UpdateProperty())->execute($this);
    }

    /**
     * 更新包含項目
     *
     * @return void
     */
    public function itemsIncluded(): void
    {
        (new UpdateItemsIncluded())->execute($this);
    }

    /**
     * 更新裝潢程度
     *
     * @return void
     */
    public function decoration(): void
    {
        (new UpdateDecoration())->execute($this);
    }

    /**
     * 更新物件費用
     *
     * @return void
     */
    public function fees(): void
    {
        (new UpdateFees())->execute($this);
    }

    /**
     * 更新附設車位
     *
     * @return void
     */
    public function carpark(): void
    {
        (new UpdateCarpark())->execute($this);
    }

    /**
     * 更新附設設備
     *
     * @return void
     */
    public function equipment(): void
    {
        (new UpdateEquipment())->execute($this);
    }

    /**
     * 更新附近生活機能資料
     *
     * @return void
     */
    public function neighborhoodLivability(): void
    {
        (new UpdateNeighborhoodLivability())->execute($this);
    }

    /**
     * 更新附近交通資料
     *
     * @return void
     */
    public function neighborhoodTransportation(): void
    {
        (new UpdateNeighborhoodTransportation())->execute($this);
    }

    /**
     * 更新聯絡人資料
     *
     * @return void
     */
    public function propertyContactPerson(): void
    {
        (new UpdatePropertyContactPerson())->execute($this);
    }

    /**
     * 更新聯絡人方式
     *
     * @return void
     */
    public function propertyContactInfo(): void
    {
        (new UpdatePropertyContactInfo())->execute($this);
    }

    /**
     * 最短租期 & 可遷入日
     *
     * @return void
     */
    public function checkInInfo(): void
    {
        (new UpdateCheckInInfo())->execute($this);
    }

    /**
     * 更新物件文件
     *
     * @return void
     */
    public function document(): void
    {
        (new UpdateDocument())->execute($this);
    }
}
