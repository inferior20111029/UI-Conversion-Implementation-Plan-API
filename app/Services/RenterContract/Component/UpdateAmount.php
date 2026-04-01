<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use App\Models\ContractBill;
use App\Support\Enum\Customization;

final class UpdateAmount
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * 更新帳單金額
     *
     * @param ContractBill $bill 帳單資料
     * @param Request $request Request
     * @param array $defaultAmount 預設金額資料
     *
     * @return void
     */
    public function execute(ContractBill $bill, Request $request, array $defaultAmount = []): void
    {
        $billAmount = (array) $request->billAmount;
        $billAmountRequest = [...$billAmount, ...$defaultAmount];

        [$create, $update, $delete] = $this->fetchHandleData($bill->id, $bill->amount, $billAmountRequest);

        $bill->amount()->upsert([...$create, ...$update], ['id']);
        $bill->amount()->whereIn('id', $delete)->forceDelete();
    }

    /**
     * 取得處理資料
     *
     * @param int $contractBillId 合約 ID
     * @param \Illuminate\Support\Collection $amount 當前擁有的金額資料
     * @param array $billAmountRequest 帳單金額
     *
     * @return array
     */
    private function fetchHandleData(int $contractBillId, Collection $amount, array $billAmountRequest): array
    {
        $create = [];
        $update = [];

        foreach ($billAmountRequest as $value) {
            $lineItem = (string) data_get($value, 'lineItem');

            $target = $amount->where('line_item', $lineItem);
            $id = $target->value('id');
            $customization = $target->first()->customization ?? Customization::TRUE->value;

            $column = compact('id') + $this->fetchBillAmountColumnData(
                compact('contractBillId', 'customization') + $value
            )->toColumnArray();

            if ($target->isNotEmpty()) {
                $update[] = $column;
                $amount->forget($target->keys()->first());

                continue;
            }

            $create[] = $column;
        }

        return [$create, $update, $amount->pluck('id')->all()];
    }
}
