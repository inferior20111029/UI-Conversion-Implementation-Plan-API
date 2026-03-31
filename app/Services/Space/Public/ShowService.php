<?php

declare(strict_types=1);

namespace App\Services\Space\Public;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;
use Symfony\Component\HttpFoundation\Response;

use App\Repositories\Space\CrmBuildingCommonBaseInfoRepository;
use App\Repositories\Space\CrmBuildingCommonInfoRepository;
use App\Repositories\Space\CrmBuildingCommonSpaceRepository;

final class ShowService extends Service
{
    use \App\Support\Trait\Public\ColumnTrait;

    public function __construct(
        private readonly CrmBuildingCommonBaseInfoRepository $crmBuildingCommonBaseInfoRepository,
        private readonly CrmBuildingCommonInfoRepository     $crmBuildingCommonInfoRepository,
        private readonly CrmBuildingCommonSpaceRepository    $crmBuildingCommonSpaceRepository,
    ) {
    }

    public function execute() {
        $crmBuildingSpace = $this->crmBuildingCommonInfoRepository->getSpaceConfigurationPaginated(
            crm('company_id'),
            crm('community_id')
        );

        return $this->paginateResponseFormat(
            $crmBuildingSpace,
            $crmBuildingSpace->getCollection()
                ->map(fn ($building) => $this->fetchPaginateResponse($building))
        );
    }

    /**
     * 公設基本資訊
     *
     * @param  string  $spaceId
     *
     * @return array
     */
    public function fetchPublicBaseData(string $spaceId): array
    {
        $crmBuildingCommonBase = $this->crmBuildingCommonBaseInfoRepository
            ->findById($spaceId);

        if ($crmBuildingCommonBase) {
            return self::transform($crmBuildingCommonBase ?? []);
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得產權基本資訊
     *
     * @param  string  $spaceId
     *
     * @return array
     */
    public function fetchPropertyData(string $spaceId): array
    {
        $crmBuildingCommonBase = $this->crmBuildingCommonSpaceRepository
            ->findByPropertyInfo($spaceId);

        if ($crmBuildingCommonBase) {
            return self::propertyTransform($crmBuildingCommonBase);
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }
}