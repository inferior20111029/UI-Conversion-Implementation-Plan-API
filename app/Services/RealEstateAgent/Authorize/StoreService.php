<?php

declare(strict_types=1);

namespace App\Services\RealEstateAgent\Authorize;

use Illuminate\Support\Arr;
use App\Support\Abstract\Service;

use App\Support\Enum\RequestFails;
use Symfony\Component\HttpFoundation\Response;
use App\Support\Data\RealEstateAgentAuthorizeData;

use App\Http\Requests\RealEstateAgent\AuthorizeRequest;

use App\Repositories\RealEstateAgent\RealEstateAgentRepository;
use App\Repositories\RealEstateAgent\RealEstateAgentAuthorizeRepository;


final class StoreService extends Service
{
    /**
     * @param  RealEstateAgentRepository  $realEstateAgentRepository
     * @param  RealEstateAgentAuthorizeRepository  $authorizeRepository
     */
    public function __construct(
        private readonly RealEstateAgentRepository $realEstateAgentRepository,
        private readonly RealEstateAgentAuthorizeRepository $authorizeRepository,
    ) {}

    /**
     * 建立授權
     *
     * @param \App\Http\Requests\RealEstateAgent\AuthorizeRequest $request Request
     *
     * @return void
     */
    public function execute(AuthorizeRequest $request): void
    {
        $column = $this->fetchColumn($request);
        $createData = $column->toColumnArray();

        $this->realEstateAgentRepository->createAuthorize($createData);
    }

    /**
     * 取得欄位資料
     *
     * @param \App\Http\Requests\RealEstateAgent\AuthorizeRequest $request Request
     *
     * @return \App\Support\Data\RealEstateAgentAuthorizeData
     */
    private function fetchColumn(AuthorizeRequest $request): RealEstateAgentAuthorizeData
    {
        return new RealEstateAgentAuthorizeData([
            'uuid' => str()->uuid()->toString(),
            'createBy' => crm('user_id')
        ] + $request->all() + crm()->only('company_id', 'community_id'));
    }

    /**
     * @param  AuthorizeRequest  $request
     *
     * @return string|null
     */
    public function identificationCode(AuthorizeRequest $request): ?string
    {
        $authorize = $this->authorizeRepository->findByIdentificationCode(
            crm('company_id'),
            crm('community_id'),
            $request->post('identificationCode')
        );

        $realEstateAgent = $this->realEstateAgentRepository->findByIdentificationCode($request->post('identificationCode'));

        if (!is_null($authorize)) {
            $this->fails(
                [
                    RequestFails::CAN_NOT_DUPLICATE_REGISTRATION->value,
                    $realEstateAgent?->uuid
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return $realEstateAgent?->uuid;
    }
}
