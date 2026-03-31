<?php

namespace App\Support\Trait\Excel;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Support\Enum\CrmHouseType;
use Rap2hpoutre\FastExcel\FastExcel;

trait FeeNumberExportTrait
{
    /**
     * 更新 FeeNumber
     *
     * @param array $data
     * @param string $type
     * @return bool
     * @throws \Exception
     */
    private function updateCrmHouseFeeNumber(array $data, string $type): bool
    {
        $feeNumber = [
            'company_id' => crm('company_id'),
            'comid'      => crm('community_id'),
        ];

        $parentItems = $this->fetchFeeNumber($feeNumber, $data, $type);

        $childrenData = [];

        foreach ($parentItems as $parentItem) {
            $parentId = $this->crmHouseFeeNumberRepository->create($parentItem)->id;
            $childrenData[] = $this->fetchFeeChildrenNumber(
                $feeNumber + ['space_id' => $parentItem['space_id']],
                $parentItem['children'],
                $type,
                (string)$parentId
            );
        }

        return $this->crmHouseFeeNumberRepository->insert(Arr::collapse($childrenData));
    }
}