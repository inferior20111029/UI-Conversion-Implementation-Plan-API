<?php

declare(strict_types=1);

namespace App\Services\Space\ConfigurationCommon;

use App\Support\Abstract\Service;

use App\Support\Tool\File\FileMagic;
use App\Repositories\Space\CrmBuildingCommonInfoRepository;
use App\Repositories\Space\CrmBuildingCommonSpaceRepository;
use App\Repositories\Space\CrmHouseFeeNumberRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\Space\CrmBuildingCommonSpaceTrait;
    use \App\Support\Trait\Space\CrmBuildingSpaceTrait;

    public function __construct(
        private readonly CrmBuildingCommonInfoRepository  $crmBuildingCommonInfoRepository,
        private readonly CrmBuildingCommonSpaceRepository $crmBuildingCommonSpaceRepository,
        private readonly CrmHouseFeeNumberRepository      $crmHouseFeeNumberRepository
    ) {
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function createInfo()
    {
        $data = self::fetchColumnData();

        $commonId = $this->crmBuildingCommonInfoRepository->insert($data)->id;

        $spaceIds = request()->post('space_id');
        $commonSpaceUpsert = array_map(fn ($space_id) => [
            'space_id' => $space_id,
            'building_common_info_id' => $commonId,
        ], $spaceIds);

        $this->crmBuildingCommonSpaceRepository->upsert($commonSpaceUpsert);
    }

    /**
     * 新增公共空建資料
     *
     * @return string|null
     * @throws \Exception
     */
    public function createCommonSpace(): ?string
    {

        $insertData = [
            'company_id'    => crm('company_id'),
            'comid'         => crm('community_id'),
            'created_at'    => now(),
            'updated_at'    => now(),
        ];

        [$water, $electric, $infoData] = self::fetchSpaceColumnData();

        $data = $infoData[0] + $insertData;

        $spaceId = $this->crmBuildingCommonSpaceRepository->create($data)->space_id;

        if (!empty($water)) {
            $this->createCrmHouseFeeNumber($spaceId, $water, 'water');
        }

        if (!empty($electric)) {
            $this->createCrmHouseFeeNumber($spaceId, $electric, 'electric');
        }

        return $spaceId;
    }
}
