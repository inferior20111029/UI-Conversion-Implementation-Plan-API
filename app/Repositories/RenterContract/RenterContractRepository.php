<?php

declare(strict_types=1);

namespace App\Repositories\RenterContract;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Fees;
use App\Models\ContractBill;
use App\Models\RenterContract;
use App\Models\ContractPaymentCycle;
use App\Models\MutualRenterContract;

use App\Support\Parameter\RenterContractParameter;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class RenterContractRepository
{
    /**
     * 取得合約全部帳單資料
     *
     * @param integer $contractId 合約 ID
     *
     * @return \Illuminate\Support\Collection
     */
    public function findAllBill(int $contractId): Collection
    {
        return RenterContract::find($contractId)
            ->withWhereHas('bill', fn(Builder|HasMany $query): Builder|HasMany => $query->isNotDelete())
            ->get();
    }

    /**
     * 取得單筆合約資料
     *
     * @param  int  $companyId
     * @param  int  $communityId
     * @param  string  $contractId
     *
     * @return Collection
     */
    public function findByUuid(int $companyId, int $communityId, string $contractId): Collection
    {
        return RenterContract::whereUuid($contractId)
            ->with('attachedEquipment', function (Builder|MorphMany $query) use ($companyId, $communityId): void {
                $query
                    ->withWhereHas('equipment', function (Builder|HasOne $equipmentQuery) use ($companyId, $communityId): void {
                        $equipmentQuery
                            ->select( 'id', 'name')
                            ->whereCompanyId($companyId)
                            ->where('comid', $communityId)
                            ->with(
                                'crmEquipmentScrap',
                            );
                    });
            })->with('equipment', fn(Builder|HasMany $query): Builder|HasMany => $query->with('crmTypeName', 'crmSystemName'))
            ->get();
    }

    /**
     * 透過 UUID 取得帳單資料
     *
     * @param integer $contractId 合約 ID
     * @param string $uuid 帳單 UUID
     *
     * @return \Illuminate\Support\Collection
     */
    public function findBillByUuid(int $contractId, string $uuid): Collection
    {
        return RenterContract::find($contractId)
            ->withWhereHas('bill', fn(Builder|HasMany $query): Builder|HasMany => $query->isNotDelete()->whereUuid($uuid))
            ->get();
    }

    /**
     * 建立帳單
     *
     * @param integer $id
     * @param \App\Support\Parameter\RenterContractParameter $parameter
     *
     * @return \App\Models\ContractBill|null
     */
    public function createBill(int $id, RenterContractParameter $parameter): ?ContractBill
    {
        $bill = RenterContract::find($id)->bill()->create($parameter->bill);
        $bill->amount()->saveMany($parameter->billAmount);

        return $bill;
    }

    /**
     * 建立共同合約
     *
     * @param int $renterContractId
     * @param \App\Support\Parameter\RenterContractParameter $parameter
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function createMutualContract(int $renterContractId, RenterContractParameter $parameter): Collection|array
    {
        $contract = $parameter->contract;
        $mutualRenterContractCreateData = collect($contract)
            ->map(function (array $value): MutualRenterContract {
                return new MutualRenterContract([
                    'mutual_contract_id' => (int) data_get($value, 'id')
                ]);
            });

        RenterContract::upsert($contract, ['id']);
        ContractPaymentCycle::upsert($parameter->paymentCycle, ['id']);
        Fees::upsert($parameter->fees, ['id']);

        return RenterContract::find($renterContractId)
            ->mutualRenterContract()
            ->saveMany($mutualRenterContractCreateData);
    }

    /**
     * 終止合約
     *
     * @param array $ids 合約 ID
     * @param array $updateData 終止更新資料
     *
     * @return int|bool
     */
    public function termination(array $ids, array $updateData): int|bool
    {
        return RenterContract::whereIn('id', $ids)->update($updateData);
    }

    /**
     * 刪除合約
     *
     * @param array $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return RenterContract::destroy($ids);
    }
}