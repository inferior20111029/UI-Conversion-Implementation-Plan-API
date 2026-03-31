<?php

declare(strict_types=1);

namespace App\Services\RealEstateAgent\Authorize;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;

use App\Support\Enum\FetchMessage;

use App\Models\RealEstateAgentAuthorize;

use App\Repositories\RealEstateAgent\RealEstateAgentAuthorizeRepository;

final class ShowService extends Service
{
    use \App\Support\Trait\RealEstateAgent\ResponseTrait;

    /**
     * @param RealEstateAgentAuthorizeRepository $realEstateAgentAuthorizeRepository
     */
    public function __construct(
        private readonly RealEstateAgentAuthorizeRepository $realEstateAgentAuthorizeRepository
    ) {}

    /**
     * 取得房仲資料
     *
     * @param string|null $uuid UUID
     *
     * @return array
     */
    public function execute(?string $uuid = null): array
    {
        $realEstateAgentData = $this->fetchData($uuid);
        $response = $this->fetchResponse($realEstateAgentData->getCollection());

        return $this->paginateResponseFormat($realEstateAgentData, $response);
    }

    /**
     * 取得房仲資料
     *
     * @param string|null $uuid
     *
     * @throws \App\Exceptions\ApiException
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function fetchData(?string $uuid = null): LengthAwarePaginator
    {
        $companyId = crm('company_id');
        $communityId = crm('community_id');

        $result = str($uuid)->isUuid()
            ? $this->realEstateAgentAuthorizeRepository->findByUuid($companyId, $communityId, $uuid)
            : $this->realEstateAgentAuthorizeRepository->findAll($companyId, $communityId);

        if ($result->isNotEmpty()) {
            return $result;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得回傳資料
     *
     * @param \Illuminate\Support\Collection $realEstateAgentData 房仲資料
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchResponse(Collection $realEstateAgentData): Collection
    {
        return $realEstateAgentData
            ->map(function (RealEstateAgentAuthorize $authorize): array {
                $realEstateAgent = $this->fetchResponseRealEstateAgentData($authorize->realEstateAgent);
                return $authorize->only('uuid') + compact('realEstateAgent');
            });
    }
}
