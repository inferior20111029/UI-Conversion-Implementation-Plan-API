<?php

declare(strict_types=1);

namespace App\Services\RealEstateAgent;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;

use App\Support\Enum\FetchMessage;

use App\Models\RealEstateAgent;

use App\Repositories\RealEstateAgent\RealEstateAgentRepository;

final class ShowService extends Service
{
    use \App\Support\Trait\RealEstateAgent\ResponseTrait;

    /**
     * @param RealEstateAgentRepository $realEstateAgentRepository
     */
    public function __construct(
        private readonly RealEstateAgentRepository $realEstateAgentRepository
    ) {}

    /**
     * 取得房仲資料
     *
     * @param string|null $uuid UUID
     *
     * @return \Illuminate\Support\Collection
     */
    public function execute(?string $uuid = null): Collection
    {
        $realEstateAgentData = $this->fetchData($uuid);
        return $this->fetchResponse($realEstateAgentData);
    }

    /**
     * 取得房仲資料
     *
     * @param string|null $uuid
     * @param array ...$options
     *
     * @throws \App\Exceptions\ApiException
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchData(?string $uuid = null, array ...$options): Collection
    {
        $companyId = intval(crm('company_id') ?? data_get($options, 'companyId') ?? 0);

        $result = str($uuid)->isUuid()
            ? $this->realEstateAgentRepository->findByUuid($companyId, $uuid)
            : $this->realEstateAgentRepository->findAll($companyId);

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
            ->map(function (RealEstateAgent $realEstateAgent): array {
                return $this->fetchResponseRealEstateAgentData($realEstateAgent);
            });
    }
}
