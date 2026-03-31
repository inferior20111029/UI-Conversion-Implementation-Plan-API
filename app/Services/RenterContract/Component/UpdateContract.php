<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

final class UpdateContract
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * 更新合約資料
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $columnData = $this->fetchContractColumnData($updateInstance->request);
        $updateData = $columnData
            ->onlyColumn(
                'name',
                'national_id_number',
                'cellphone',
                'birthday',
                'start_time',
                'end_time',
                'allow_declare',
                'allow_early_termination',
                'allow_sublease',
                'restore',
                'remark'
            )
            ->noHaveMacro()
            ->toColumnArray();

        $updateInstance->contractData->update($updateData);
    }
}
