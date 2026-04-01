<?php

declare(strict_types=1);

namespace App\Services\Space\ConfigurationCommon;

use App\Support\Abstract\Service;
use App\Support\Tool\File\FileMagic;
use App\Repositories\Space\CrmHouseFeeNumberRepository;
use App\Repositories\Space\CrmBuildingCommonInfoRepository;
use App\Repositories\Space\CrmBuildingCommonSpaceRepository;

final class UpdateService extends Service
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
     * 取的公共空間資訊
     *
     * @param  int  $id
     *
     * @return mixed
     */
    public function execute(int $id)
    {
        return $this->crmBuildingCommonInfoRepository->findById($id)
            ->map(fn ($item) => $this->updateCommonColumn($item));
    }

    /**
     * 取的公共空間資料
     *
     * @param  string  $spaceId
     *
     * @return mixed
     */
    public function executeSpace(string $spaceId)
    {
        $crmBuildingSpace = $this->crmBuildingCommonSpaceRepository
            ->findByUuid($spaceId)
            ->toArray();

        return $this->updateColumn($crmBuildingSpace, 'public');
    }

    /**
     * 更新公共空間配置
     *
     * @param string $id
     * @return void
     */
    public function update(string $id): void
    {
        $data = self::fetchColumnData('edit');

        $this->crmBuildingCommonInfoRepository->upsert($data + ['id' => $id]);

        $spaceIds = request()->post('space_id');
        $commonSpaceUpsert = array_map(fn ($space_id) => [
            'space_id' => $space_id,
            'building_common_info_id' => $id,
        ], $spaceIds);

        $this->crmBuildingCommonSpaceRepository->upsert($commonSpaceUpsert);

        $delSpaceIds = request()->post('del_space_id');

        if (is_array($delSpaceIds) && count($delSpaceIds) > 0) {
            $this->crmBuildingCommonSpaceRepository->forceDelete($delSpaceIds);
        }
    }

    /**
     * 更新公共空建資料
     *
     * @param  string  $spaceId
     *
     * @return string|null
     * @throws \Exception
     */
    public function updateCommonSpace(string $spaceId): ?string
    {
        $insertData = [
            'comid'         => crm('community_id'),
            'updated_at'    => now(),
        ];

        [$water, $electric, $infoData] = self::fetchSpaceColumnData();

        $data = $infoData[0] + $insertData;

        $this->crmBuildingCommonSpaceRepository->update($spaceId, $data);

        $processedElectric = $this->processItems($electric, $spaceId);
        $processedWater    = $this->processItems($water, $spaceId);

        $this->crmHouseFeeNumberRepository->upsert([...$processedWater, ...$processedElectric]);

        if (!empty(request()->post('del_fee_number'))) {
            $this->crmHouseFeeNumberRepository->forceDeleteIds(request()->post('del_fee_number'));
        }

        if (!empty(request()->post('del_fee_number_children'))) {
            $this->crmHouseFeeNumberRepository->forceDeleteChildrenIds(request()->post('del_fee_number_children'));
        }

        return $spaceId;
    }
}
