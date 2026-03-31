<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Component;

use App\Models\PropertyContactInfo;

final class UpdatePropertyContactInfo
{
    use \App\Support\Trait\PropertyManage\ColumnTrait;

    /**
     * 更新聯絡人資訊資料
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $contactInfoRequest = (array) $updateInstance->request->post('contactInfo');

        if (empty($contactInfoRequest)) {
            $updateInstance->propertyData->propertyContactInfo()->forceDelete();
            return;
        }

        $updateInstance->propertyData->propertyContactInfo()->forceDelete();

        $contact = collect($contactInfoRequest['type'])
            ->map(fn ($type, $key) => [
                'info' => $contactInfoRequest['info'][$key] ?? null,
                'type' => $type,
            ])
            ->filter(fn ($item) => !is_null($item['type']))
            ->values()
            ->toArray();

        $propertyContactData = array_map(function (array $contact): PropertyContactInfo {
            return new PropertyContactInfo(
                $this->fetchContactInfoColumnData($contact)->noHaveMacro()->toColumnArray()
            );
        }, $contact);

        $updateInstance->propertyData->propertyContactInfo()->saveMany($propertyContactData);
    }
}
