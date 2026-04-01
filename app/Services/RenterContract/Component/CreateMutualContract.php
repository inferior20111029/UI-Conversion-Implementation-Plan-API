<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use Illuminate\Support\Arr;

use App\Support\Data\FeesData;
use App\Support\Data\RenterContractData;
use App\Support\Data\ContractPaymentCycleData;
use App\Support\Parameter\RenterContractParameter;

use App\Models\RenterContract;
use App\Models\CrmParkingSpace;

use App\Repositories\RenterContract\RenterContractCacheRepository;

use App\Repositories\RenterContract\RenterContractRepository;

final class CreateMutualContract
{
    /**
     * 附設車位
     * @var array
     */
    private readonly array $attachedCarpark;

    /**
     * @param RenterContract $contract 合約資料
     */
    public function __construct(
        private readonly RenterContract $contract
    ) {
        $this->attachedCarpark = Arr::map(
            $contract->attachedCarpark->toArray(),
            function (array $carparkData, int $key): array {
                $carparkData['renterContractId'] = (int) $this->contract->id + 1 + $key;
                return $carparkData;
            }
        );
    }

    /**
     * 建立共同合約
     *
     * @return void
     */
    public function execute(): void
    {
        $parameter = new RenterContractParameter([
            'contract' => $this->contractParameter(),
            'paymentCycle' => $this->paymentCycleParameter(),
            'fees' => $this->feesParameter()
        ]);

        (new RenterContractRepository())->createMutualContract($this->contract->id, $parameter);
    }

    /**
     * 合約建立資料
     * @return array
     */
    private function contractParameter(): array
    {
        $contract = $this->contract->getAttributes();

        return Arr::map(
            $this->attachedCarpark,
            function (array $carparkData) use ($contract): array {
                $id = (int) Arr::get($carparkData, 'renterContractId');
                $contract['uuid'] = str()->uuid()->toString();
                $contract['taggable_type'] = CrmParkingSpace::class;
                $contract['taggable_id'] = (string) Arr::get($carparkData, 'crm_parking_space_id');

                return (new RenterContractData($contract))
                    ->replace(compact('id'))
                    ->toColumnArray();
            }
        );
    }

    /**
     * 付款週期建立資料
     * @return array
     */
    private function paymentCycleParameter(): array
    {
        $paymentCycle = $this->contract->paymentCycle->getAttributes();

        return Arr::map(
            $this->attachedCarpark,
            function (array $carparkData) use ($paymentCycle): array {
                $paymentCycle['renter_contract_id'] = (int) Arr::get($carparkData, 'renterContractId');
                return (new ContractPaymentCycleData($paymentCycle))->toColumnArray();
            }
        );
    }

    /**
     * 費用建立資料
     * @return array
     */
    private function feesParameter(): array
    {
        $fees = $this->contract->fees->getAttributes();

        return Arr::map(
            $this->attachedCarpark,
            function (array $carparkData) use ($fees): array {
                $fees['price'] = (int) Arr::get($carparkData, 'price');
                $fees['taggable_type'] = RenterContract::class;
                $fees['taggable_id'] = (int) Arr::get($carparkData, 'renterContractId');

                return (new FeesData($fees))
                    ->excludeColumn('renter_contract_id')
                    ->toColumnArray();
            }
        );
    }

    /**
     * 暫存建立資料
     *
     * @param $hasCache
     *
     * @return void
     */
    public function cacheParameter(int $hasCache): void
    {
        $id = $hasCache == 1 ? (int) $this->contract->id : 0;
        $spaceId = $this->contract->taggable_id;

        $renterContractId = (new RenterContractCacheRepository())->findSpace($spaceId)?->renter_contract_id;

        if(!is_null($renterContractId)) {
            (new RenterContractRepository())->destroy([$renterContractId]);
        }

        (new RenterContractCacheRepository())->update([
            'renter_contract_id' => $id,
            'space_id' => $spaceId
        ]);
    }
}
