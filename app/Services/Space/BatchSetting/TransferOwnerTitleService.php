<?php

declare(strict_types=1);

namespace App\Services\Space\BatchSetting;

use App\Support\Abstract\Service;

use App\Repositories\HouseholdType\CrmPropertyInfoRepository;
use App\Repositories\HouseholdType\CrmPropertyTransactionInfoRepository;

final class TransferOwnerTitleService extends Service
{
    /**
     * @param  CrmPropertyInfoRepository             $crmPropertyInfoRepository
     * @param  CrmPropertyTransactionInfoRepository  $crmPropertyTransactionInfoRepository
     */
    public function __construct(
        private readonly CrmPropertyInfoRepository            $crmPropertyInfoRepository,
        private readonly CrmPropertyTransactionInfoRepository $crmPropertyTransactionInfoRepository,
    ) {
    }

    /**
     * 專有空間 批次過戶
     *
     * @return void
     */
    public function execute($request): void
    {
        $spaceIds     = $request->post('space_id');
        $transferDate = $request->post('transfer_date');

        $propertyInfoData = $this->crmPropertyInfoRepository
            ->findByEdit($spaceIds)
            ->map(fn ($propertyInfo) => $this->mapPropertyInfo($propertyInfo, $transferDate));

        $this->crmPropertyInfoRepository->upsert($propertyInfoData->toArray());

        $transactionIds = $propertyInfoData->pluck('id')->toArray();

        $crmPropertyTransactionInfo = $this->crmPropertyTransactionInfoRepository->findBySpaceIds($spaceIds, $transactionIds);

        $filteredTransactionInfo = $crmPropertyTransactionInfo
            ->filter(fn ($transactionInfo) => $transactionInfo->mode === 'related_promiser')
            ->map(fn ($transactionInfo) => $this->mapToInhabitantIfNotExist($crmPropertyTransactionInfo, $transactionInfo))
            ->filter()
            ->values();

        $this->crmPropertyTransactionInfoRepository->insert($filteredTransactionInfo->toArray());
    }

    /**
     * @param $propertyInfo
     * @param $transferDate
     *
     * @return array
     */
    private function mapPropertyInfo($propertyInfo, $transferDate): array
    {
        return [
            'id'             => $propertyInfo['id'],
            'space_id'       => $propertyInfo['space_id'],
            'sign_date'      => $propertyInfo['sign_date'],
            'build_date'     => $propertyInfo['build_date'],
            'transfer_item'  => $propertyInfo['transfer_item'],
            'transfer_cause' => $propertyInfo['transfer_cause'],
            'transfer_date'  => $transferDate,
        ];
    }

    /**
     * @param $allTransactions
     * @param $transactionInfo
     *
     * @return string[]|null
     */
    private function mapToInhabitantIfNotExist($allTransactions, $transactionInfo)
    {
        $hasInhabitant = $allTransactions->where('property_info_id', $transactionInfo->property_info_id)
            ->where('client_id', $transactionInfo->client_id)
            ->where('mode', 'inhabitant')
            ->count();

        if ($hasInhabitant !== 0) {
            return null;
        }

        return [
            'mode'             => 'inhabitant',
            "mode_sort"        => 2,
            "property_info_id" => $transactionInfo->property_info_id,
            "space_id"         => $transactionInfo->space_id,
            "client_id"        => $transactionInfo->client_id,
            "portion_percent"  => $transactionInfo->portion_percent,
            "portion_area"     => $transactionInfo->portion_area,
            "portion_square_meter" => $transactionInfo->portion_area,
            "type"             => $transactionInfo->type,
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ];
    }
}