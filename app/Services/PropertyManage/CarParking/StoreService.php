<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\CarParking;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

use App\Support\Abstract\Service;
use App\Support\Enum\CreateMessage;
use App\Support\Parameter\PropertyManageParameter;

use App\Services\PropertyManage\Component\InsertData;

use App\Http\Requests\RenterContract\SpaceRequest;

use App\Repositories\PropertyManage\PropertyRepository;

final class StoreService extends Service
{
    /**
     * @param PropertyRepository $propertyRepository
     */
    public function __construct(
        private readonly PropertyRepository $propertyRepository
    ) {
    }

    /**
     *  建立物件
     *
     * @param  Request  $request
     *
     * @return int|null
     */
    public function execute(Request $request): ?int
    {
        $createParameter = $this->fetchCreateParameter($request);

        return $this->create($createParameter);
    }

    /**
     * 取得所有建立參數
     *
     * @param SpaceRequest $request Request
     *
     * @return \App\Support\Parameter\PropertyManageParameter
     */
    private function fetchCreateParameter(Request $request): PropertyManageParameter
    {
        $insertData = new InsertData($request);

        return new PropertyManageParameter([
            'property'      => $insertData->contract(),
            'fees'          => $insertData->fees(),
            'document'      => $insertData->document(),
            'checkInInfo'   => $insertData->checkInInfo(),
            'contactPerson' => $insertData->contactPerson(),
            'contactInfo'   => $insertData->contactInfo(),
        ]);
    }

    /**
     * 建立資料
     *
     * @param string $spaceId 戶別 ID
     * @param \App\Support\Parameter\PropertyManageParameter $parameter 物件建立參數
     *
     * @throws \App\Exceptions\ApiException
     *
     * @return int|null
     */
    private function create(PropertyManageParameter $parameter): ?int
    {
        $create = $this->propertyRepository->createCarParkingProperty($parameter);

        if (empty($create)) {
            $this->fails(CreateMessage::FAILS->value, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $create->id;
    }
}
