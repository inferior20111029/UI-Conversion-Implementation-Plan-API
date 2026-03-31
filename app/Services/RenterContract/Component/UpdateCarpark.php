<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use Illuminate\Support\Collection;

final class UpdateCarpark
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * 更新附設車位
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $carpark = $updateInstance->contractData->attachedCarpark;
        $carparkRequest = (array) $updateInstance->request->post('carpark');

        if ($carpark->isNotEmpty() && empty($carparkRequest)) {
            $updateInstance->contractData->attachedCarpark()->forceDelete();
            return;
        }

        if (empty($carparkRequest)) {
            return;
        }

        [$create, $update, $delete] = $this->fetchHandleData($updateInstance, $carpark, $carparkRequest);

        $updateInstance->contractData->attachedCarpark()->upsert([...$create, ...$update], ['id']);
        $updateInstance->contractData->attachedCarpark()->whereIn('id', $delete)->forceDelete();
    }

    /**
     * 取得處理資料
     *
     * @param \App\Services\RenterContract\Component\UpdateInstance $updateInstance 更新實例
     * @param \Illuminate\Support\Collection $carpark 當前擁有的停車位
     * @param array $carparkRequest 停車位資料
     *
     * @return array
     */
    private function fetchHandleData(UpdateInstance $updateInstance, Collection $carpark, array $carparkRequest): array
    {
        $morph = $this->morphColumn($updateInstance->contractData);

        $create = [];
        $update = [];

        foreach ($carparkRequest as $value) {
            $type = (string) data_get($value, 'type');

            $target = $carpark->where('type', $type);
            $id = $target->value('id');

            $columnData = $this->fetchCarparkColumnData($value)
                ->replace(compact('id') + $morph)
                ->toColumnArray();

            if ($target->isNotEmpty()) {
                $update[] = $columnData;
                $carpark->forget($target->keys()->first());

                continue;
            }

            $create[] = $columnData;
        }

        return [$create, $update, $carpark->pluck('id')->all()];
    }
}
