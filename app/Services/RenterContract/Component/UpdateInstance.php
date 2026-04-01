<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use Illuminate\Http\Request;

use App\Models\RenterContract;

final class UpdateInstance
{
    /**
     * @param \App\Models\RenterContract $contractData 合約資料
     * @param \Illuminate\Http\Request $request Request
     */
    public function __construct(
        public readonly RenterContract $contractData,
        public readonly Request $request
    ) {}

    /**
     * 更新合約資料
     *
     * @return void
     */
    public function contract(): void
    {
        (new UpdateContract())->execute($this);
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
     * 更新合約相關人員
     *
     * @return void
     */
    public function persons(): void
    {
        (new UpdatePerson())->execute($this);
    }

    /**
     * 更新合約文件
     *
     * @return void
     */
    public function document(): void
    {
        (new UpdateDocument())->execute($this);
    }

    /**
     * 更新合約付款週期
     *
     * @return void
     */
    public function paymentCycle(): void
    {
        (new UpdatePaymentCycle())->execute($this);
    }

    /**
     * 更新合約通知
     *
     * @return void
     */
    public function notify(): void
    {
        (new UpdateNotify())->execute($this);
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
     * 更新合約費用
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
     * 更新銀行帳戶
     *
     * @return void
     */
    public function bank(): void
    {
        (new UpdateBank())->execute($this);
    }

    /**
     * 更新暫存
     *
     * @return void
     */
    public function cache(): void
    {
        (new UpdateCache())->execute($this);
    }
}
