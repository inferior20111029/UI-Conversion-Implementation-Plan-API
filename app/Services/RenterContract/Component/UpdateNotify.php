<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use App\Models\ContractNotify;
use Illuminate\Support\Collection;

final class UpdateNotify
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * 更新合約通知
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $notify = $updateInstance->contractData->notify;
        $notifyRequest = (array) $updateInstance->request->post('notify');

        if ($notify->isNotEmpty() && empty($notifyRequest)) {
            $updateInstance->contractData->notify()->forceDelete();
            return;
        }

        if (empty($notifyRequest)) {
            return;
        }

        [$create, $update, $delete] = $this->fetchHandleData($updateInstance, $notify, $notifyRequest);

        $updateInstance->contractData->notify()->upsert([...$create, ...$update], ['id']);
        $updateInstance->contractData->notify()->whereIn('id', $delete)->forceDelete();
    }

    /**
     * 取得處理資料
     *
     * @param \App\Services\RenterContract\Component\UpdateInstance $updateInstance 更新實例
     * @param \Illuminate\Support\Collection $notify 當前擁有的通知資料
     * @param array $notifyRequest 通知資料
     *
     * @return array
     */
    private function fetchHandleData(UpdateInstance $updateInstance, Collection $notify, array $notifyRequest): array
    {
        $relationKey = $this->fetchContractRelationKey(ContractNotify::class);
        $contractColumn = $this->contractColumn($updateInstance->contractData, $relationKey);

        $create = [];
        $update = [];

        foreach ($notifyRequest as $value) {
            $target = $notify->where('type', data_get($value, 'type'));
            $id = $target->value('id');

            $columnData = $this->fetchContractNotifyColumnData($value)
                ->excludeColumn('already_trigger')
                ->replace([...compact('id'), ...$contractColumn, ...$this->uuidColumn($target->value('uuid'))])
                ->toColumnArray();

            if ($target->isNotEmpty()) {
                $update[] = $columnData;
                $notify->forget($target->keys()->first());

                continue;
            }

            $create[] = $columnData;
        }

        return [$create, $update, $notify->pluck('id')->all()];
    }
}
