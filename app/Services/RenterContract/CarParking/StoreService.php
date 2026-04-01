<?php

declare(strict_types=1);

namespace App\Services\RenterContract\CarParking;

use App\Models\RenterContract;
use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;
use App\Support\Enum\CreateMessage;
use App\Support\Parameter\RenterContractParameter;

use App\Services\RenterContract\Component\InsertData;

use App\Http\Requests\RenterContract\CarParkingRequest;

use App\Models\CrmParkingSpace;

use App\Repositories\Space\CrmParkingSpaceRepository;
use App\Services\RenterContract\Component\CreateMutualContract;

final class StoreService extends Service
{
    use \App\Support\Trait\Space\CheckTrait;

    /**
     * @param CrmParkingSpaceRepository $crmParkingSpaceRepository
     */
    public function __construct(
        private readonly CrmParkingSpaceRepository $crmParkingSpaceRepository
    ) {}

    /**
     * 建立合約
     *
     * @param CarParkingRequest $request
     * @return void
     */
    public function execute(CarParkingRequest $request): void
    {
        $carParkingId = (string) $request?->carParkingId;

        $createParameter = $this->fetchCreateParameter($request);
        $contract = $this->create($carParkingId, $createParameter);

        (new CreateMutualContract($contract))->cacheParameter((int) $request->post('cacheState'));
    }

    /**
     * 取得車位資料
     * @param string $carParkingId
     * @throws \App\Exceptions\ApiException
     * @return \App\Models\CrmParkingSpace
     */
    public function fetchCarParkingData(string $carParkingId): CrmParkingSpace
    {
        $carParkingData = $this->crmParkingSpaceRepository
            ->findByUuid(
                $carParkingId,
                crm('company_id'),
                crm('community_id')
            );

        if (!empty($carParkingData)) {
            return $carParkingData;
        }

        $this->fails(FetchMessage::NOT_FOUND_CAR_PARKING_DATA->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得所有建立參數
     *
     * @param CarParkingRequest $request Request
     *
     * @return \App\Support\Parameter\RenterContractParameter
     */
    private function fetchCreateParameter(CarParkingRequest $request): RenterContractParameter
    {
        $insertData = new InsertData($request);

        return new RenterContractParameter([
            'contract' => $insertData->contract(),
            'persons' => $insertData->persons(),
            'document' => $insertData->document(),
            'paymentCycle' => $insertData->paymentCycle(),
            'notify' => $insertData->notify(),
            'fees' => $insertData->fees(),
            'bank' => $insertData->bank()
        ]);
    }

    /**
     * 建立資料
     *
     * @param string $carParkingId 車位 ID
     * @param \App\Support\Parameter\RenterContractParameter $parameter 合約建立參數
     *
     * @throws \App\Exceptions\ApiException
     *
     * @return \App\Models\RenterContract
     */
    private function create(string $carParkingId, RenterContractParameter $parameter): RenterContract
    {
        $create = $this->crmParkingSpaceRepository->createContract($carParkingId, $parameter);

        if (!empty($create)) {
            return $create;
        }

        $this->fails(CreateMessage::FAILS->value, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
