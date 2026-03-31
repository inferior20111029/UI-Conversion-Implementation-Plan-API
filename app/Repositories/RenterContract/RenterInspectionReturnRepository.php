<?php

declare(strict_types=1);

namespace App\Repositories\RenterContract;

use App\Models\RenterInspectionReturn;

class RenterInspectionReturnRepository
{
    /**
     * @param $data
     *
     * @return int
     */
    public function insertGetId($data): int
    {
        return RenterInspectionReturn::insertGetId($data);
    }

    /**
     * @param  int  $companyId
     * @param  int  $communityId
     * @param  int  $contractId
     * @param  int  $type
     *
     * @return RenterInspectionReturn|null
     */
    public function findByContractId(int $companyId, int $communityId, int $contractId, int $type): ?RenterInspectionReturn
    {
         return RenterInspectionReturn::whereRenterContractId($contractId)
                ->where('type', $type)
                ->withWhereHas('inspectionReturnEquipment', function ($query) use ($companyId, $communityId): void {
                    $query->withWhereHas('equipment', function ($equipmentQuery) use ($companyId, $communityId): void {
                        $equipmentQuery
                            ->select( 'id', 'name', 'updated_at')
                            ->whereCompanyId($companyId)
                            ->where('comid', $communityId)
                            ->with('crmEquipmentScrap');
                    });
                })
             ->orderBy('id', 'desc')
             ->first();
    }

    /**
     * @param  array  $updateData
     *
     * @return bool
     */
    public function update(array $updateData): bool
    {
       return RenterInspectionReturn::updateOrCreate([
            'renter_contract_id' => $updateData['renter_contract_id'],
            'type'               => $updateData['type'],
        ])->update([
            'file_id' => $updateData['file_id'],
        ]);
    }
}