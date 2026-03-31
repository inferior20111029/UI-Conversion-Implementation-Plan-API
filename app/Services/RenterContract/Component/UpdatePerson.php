<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use Illuminate\Support\Collection;

use App\Models\ContractAssociatedPersons;

final class UpdatePerson
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * 更新合約相關人員
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $persons = $updateInstance->contractData->persons;
        $personsRequest = (array) $updateInstance->request->post('associatedPersons');

        if ($persons->isNotEmpty() && empty($personsRequest)) {
            $updateInstance->contractData->persons()->forceDelete();
            return;
        }

        if (empty($personsRequest)) {
            return;
        }

        [$create, $update, $delete] = $this->fetchHandleData($updateInstance, $persons, $personsRequest);

        $updateInstance->contractData->persons()->upsert([...$create, ...$update], ['id']);
        $updateInstance->contractData->persons()->whereIn('id', $delete)->forceDelete();
    }

    /**
     * 取得處理資料
     * @param \App\Services\RenterContract\Component\UpdateInstance $updateInstance 更新實例
     * @param \Illuminate\Support\Collection $persons 當前擁有的相關人員
     * @param array $personsRequest 相關人員資料
     *
     * @return array
     */
    private function fetchHandleData(UpdateInstance $updateInstance, Collection $persons, array $personsRequest): array
    {
        $contractRelationKey = $this->fetchContractRelationKey(ContractAssociatedPersons::class);
        $contractColumn = $this->contractColumn($updateInstance->contractData, $contractRelationKey);

        $create = [];
        $update = [];

        foreach ($personsRequest as $person) {
            $target = $persons
                ->where('type', data_get($person, 'type'))
                ->where('national_id_number', data_get($person, 'nationalIdNumber'));

            $id = $target->value('id');

            $columnData = $this->fetchAssociatedPersonsColumnData($person)
                ->replace([...compact('id'), ...$contractColumn, ...$this->uuidColumn($target->value('uuid'))])
                ->toColumnArray();

            if ($target->isNotEmpty()) {
                $update[] = $columnData;
                $persons->forget($target->keys()->first());

                continue;
            }

            $create[] = $columnData;
        }

        return [$create, $update, $persons->pluck('id')->all()];
    }
}
