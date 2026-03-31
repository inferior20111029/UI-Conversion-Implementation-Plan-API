<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Bill;

use App\Support\Abstract\Service;
use App\Support\Parameter\RenterContractParameter;

use App\Http\Requests\RenterContract\BillRequest;

use App\Repositories\RenterContract\RenterContractRepository;

use App\Models\RenterContract;

use App\Services\RenterContract\Component\InsertData;

final class StoreService extends Service
{
    use \App\Support\Trait\RenterContract\BillTrait;

    /**
     * @param RenterContractRepository $renterContractRepository
     */
    public function __construct(
        private readonly RenterContractRepository $renterContractRepository
    ) {
    }

    /**
     * 建立帳單資料
     *
     * @param \App\Models\RenterContract $contract 合約資料
     * @param \App\Http\Requests\RenterContract\BillRequest $request Request
     *
     * @return void
     */
    public function execute(RenterContract $contract, BillRequest $request): void
    {
        $parameter = $this->fetchParameter($contract, $request);
        $this->renterContractRepository->createBill($contract->id, $parameter);
    }

    /**
     * 取得合約參數
     *
     * @param \App\Models\RenterContract $contract 合約資料
     * @param \App\Http\Requests\RenterContract\BillRequest $request Request
     *
     * @return \App\Support\Parameter\RenterContractParameter
     */
    private function fetchParameter(RenterContract $contract, BillRequest $request): RenterContractParameter
    {
        return new RenterContractParameter([
            'bill' => (new InsertData($request))->bill(),
            'billAmount' => $this->fetchBillAmountInsertData($contract, $request)
        ]);
    }

    /**
     * 取得帳單金額建立資料
     *
     * @param \App\Models\RenterContract $contract 合約資料
     * @param \App\Http\Requests\RenterContract\BillRequest $request Request
     *
     * @return array
     */
    private function fetchBillAmountInsertData(RenterContract $contract, BillRequest $request): array
    {
        $insertData = new InsertData($request);

        $billAmountRequest = (array) $request->post('billAmount');
        $customizationBillAmount = $insertData->billAmount($billAmountRequest, isCustomization: true);

        $defaultAmount = $this->fetchSpaceDefaultAmount($contract);
        $defaultBillAmount = $insertData->billAmount($defaultAmount);

        return [...$defaultBillAmount, ...$customizationBillAmount];
    }
}
