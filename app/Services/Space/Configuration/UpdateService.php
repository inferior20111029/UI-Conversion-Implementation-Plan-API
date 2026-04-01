<?php

declare(strict_types=1);

namespace App\Services\Space\Configuration;

use Illuminate\Support\Facades\DB;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmHouseFeeNumberRepository;

final class UpdateService extends Service
{
    use \App\Support\Trait\Space\CrmBuildingSpaceTrait;

    public function __construct(
        private readonly CrmBuildingSpaceRepository  $crmBuildingSpaceRepository,
        private readonly CrmHouseFeeNumberRepository $crmHouseFeeNumberRepository,
    ) {
    }

    /**
     * 回傳空間組態編輯資料
     *
     * @return array
     */
    public function execute(string $uuid): array
    {
        $crmBuildingSpace = $this->crmBuildingSpaceRepository
            ->findByUuid($uuid, crm('company_id'))
            ->toArray();

        return $this->updateColumn($crmBuildingSpace);
    }

    /**
     * 更新空間配置
     *
     * @param string $id
     * @return void
     */
    public function update(string $spaceId): void
    {
        $postData = collect(request()->only([
            'district', 'building', 'staircase', 'floor', 'household',
            'doorplate', 'block_id', 'tax_id', 'main_application',
            'use_license_id', 'building_build_licence_id', 'land_use_zoning',
            'extent_of_ownership', 'locate', 'del_fee_number',
            'del_fee_number_children', 'water', 'electric'
        ]));

        $infoData = $this->explodeData($postData->get('district'), 'district') +
            $this->explodeData($postData->get('building'), 'building') +
            $this->explodeData($postData->get('staircase'), 'staircase') +
            $this->explodeData($postData->get('floor'), 'floor') +
            $this->explodeData($postData->get('household'), 'household');

        $now = now();
        $insertData = [
            'company_id'              => crm('company_id'),
            'comid'                   => crm('community_id'),
            'doorplate'               => $postData->get('doorplate'),
            'block_id'                => $postData->get('block_id'),
            'tax_id'                  => $postData->get('tax_id'),
            'main_application'        => $postData->get('main_application'),
            'use_license_id'          => $postData->get('use_license_id'),
            'land_use_zoning'         => $postData->get('land_use_zoning'),
            'building_build_licence_id'=> $postData->get('building_build_licence_id'),
            'extent_of_ownership'     => $postData->get('extent_of_ownership'),
            'locate'                  => $postData->get('locate'),
            'updated_at'              => $now,
        ];

        $data = $infoData + $insertData;

        DB::transaction(function () use ($spaceId, $data, $postData) {
            $this->crmBuildingSpaceRepository->update($spaceId, $data);

            $processedElectric = $this->processItems($postData->get('electric'), $spaceId);
            $processedWater    = $this->processItems($postData->get('water'), $spaceId);

            $this->crmHouseFeeNumberRepository->upsert([...$processedWater, ...$processedElectric]);

            if (!empty($postData->get('del_fee_number'))) {
                $this->crmHouseFeeNumberRepository->forceDeleteIds($postData->get('del_fee_number'));
            }

            if (!empty($postData->get('del_fee_number_children'))) {
                $this->crmHouseFeeNumberRepository->forceDeleteChildrenIds($postData->get('del_fee_number_children'));
            }
        });
    }
}