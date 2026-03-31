<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Space;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Enum\CreateMessage;
use App\Support\Parameter\RenterContractParameter;

use App\Services\RenterContract\Component\InsertData;
use App\Services\RenterContract\Component\CreateMutualContract;

use App\Http\Requests\RenterContract\SpaceRequest;

use App\Models\RenterContract;

use App\Repositories\Space\CrmBuildingSpaceRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\Space\CheckTrait;

    /**
     * @param CrmBuildingSpaceRepository $crmBuildingSpaceRepository
     */
    public function __construct(
        private readonly CrmBuildingSpaceRepository $crmBuildingSpaceRepository
    ) {}

    /**
     * 建立合約
     *
     * @param SpaceRequest $request
     * @return void
     */
    public function execute(SpaceRequest $request): void
    {
        $spaceId = (string) $request?->spaceId;
        $this->spaceExists($spaceId);

        $createParameter = $this->fetchCreateParameter($request);
        $contract = $this->create($spaceId, $createParameter);

        if ($createParameter->hasCarparkData()) {
            (new CreateMutualContract($contract))->execute();
        }

         (new CreateMutualContract($contract))->cacheParameter((int) $request->post('cacheState'));
    }

    /**
     * 取得所有建立參數
     *
     * @param SpaceRequest $request Request
     *
     * @return \App\Support\Parameter\RenterContractParameter
     */
    private function fetchCreateParameter(SpaceRequest $request): RenterContractParameter
    {
        $insertData = new InsertData($request);

        return new RenterContractParameter([
            'contract' => $insertData->contract(),
            'itemsIncluded' => $insertData->itemsIncluded(),
            'persons' => $insertData->persons(),
            'document' => $insertData->document(),
            'paymentCycle' => $insertData->paymentCycle(),
            'notify' => $insertData->notify(),
            'decoration' => $insertData->decoration(),
            'fees' => $insertData->fees(),
            'carpark' => $insertData->carpark(),
            'equipment' => $insertData->equipment(),
            'bank' => $insertData->bank(),
        ]);
    }

    /**
     * 建立資料
     *
     * @param string $spaceId 戶別 ID
     * @param \App\Support\Parameter\RenterContractParameter $parameter 合約建立參數
     *
     * @throws \App\Exceptions\ApiException
     *
     * @return \App\Models\RenterContract
     */
    private function create(string $spaceId, RenterContractParameter $parameter): RenterContract
    {
        $create = $this->crmBuildingSpaceRepository->createContract($spaceId, $parameter);

        if (!empty($create)) {
            return $create;
        }

        $this->fails(CreateMessage::FAILS->value, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
