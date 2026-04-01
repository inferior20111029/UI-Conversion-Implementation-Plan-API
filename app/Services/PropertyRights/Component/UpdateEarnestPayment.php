<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use App\Support\Data\SpaceEarnestPaymentData;

final class UpdateEarnestPayment
{
    use \App\Support\Trait\PropertyRights\ColumnTrait;

    /**
     * 更新斡旋金
     *
     * @param UpdateInstance $updateInstance 更新實例
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $spaceEarnestPayment = $updateInstance->spaceData->spaceEarnestPayment;
        $earnestPaymentRequest = (array) $updateInstance->request->post('earnestPayment');

        if ($spaceEarnestPayment->isNotEmpty() && empty($earnestPaymentRequest)) {
            $updateInstance->spaceData->spaceEarnestPayment()->forceDelete();
            return;
        }

        if (empty($earnestPaymentRequest)) {
            return;
        }

        [$update, $delete] = $this->fetchHandleData($updateInstance, $spaceEarnestPayment, $earnestPaymentRequest);

        $updateInstance->spaceData->spaceEarnestPayment()->upsert($update, ['uuid']);
        $updateInstance->spaceData->spaceEarnestPayment()->whereIn('id', $delete)->forceDelete();
    }

    /**
     * 取得處理資料
     *
     * @param \App\Services\PropertyRights\Component\UpdateInstance $updateInstance
     * @param \Illuminate\Support\Collection $spaceEarnestPayment
     * @param array $earnestPaymentRequest
     *
     * @return array
     */
    private function fetchHandleData(UpdateInstance $updateInstance, Collection $spaceEarnestPayment, array $earnestPaymentRequest): array
    {
        $spaceId = $updateInstance->fetchSpaceId();
        $update = Arr::map($earnestPaymentRequest, function (array $value) use ($spaceId): array {
            Arr::set($value, 'amountOfMoney', (int) Arr::get($value, 'amountOfMoney'));
            $uuid = Arr::get($value, 'uuid') ?? str()->uuid()->toString();

            return (new SpaceEarnestPaymentData(compact('uuid', 'spaceId') + $value))->toColumnArray();
        });

        $delete = $spaceEarnestPayment
            ->whereNotIn('uuid', Arr::pluck($earnestPaymentRequest, 'uuid'))
            ->pluck('id');

        return [$update, $delete];
    }
}
