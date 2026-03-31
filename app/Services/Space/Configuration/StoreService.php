<?php

declare(strict_types=1);

namespace App\Services\Space\Configuration;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmHouseFeeNumberRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\Space\CrmBuildingSpaceTrait;

    public function __construct(
        private readonly CrmBuildingSpaceRepository  $crmBuildingSpaceRepository,
        private readonly CrmHouseFeeNumberRepository $crmHouseFeeNumberRepository
    ) {
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function create()
    {
        $district  = request()->post('district');
        $building  = request()->post('building');
        $staircase = request()->post('staircase');
        $floor     = request()->post('floor');
        $household = request()->post('household');
        $doorplate = request()->post('doorplate'); // 門牌
        $blockId   = request()->post('block_id'); // 建號
        $taxId     = request()->post('tax_id'); // 稅籍號碼
        $type      = request()->post('type'); // 稅籍號碼
        $extentOfOwnership = request()->post('extent_of_ownership'); // 權利範圍
        $locate    = request()->post('locate'); // 坐落
        $landUseZoning = request()->post('land_use_zoning'); // 土地使用分區 住宅:residence 商用: commercial

        $mainApplication = request()->post('main_application'); // 主要用途
        $useLicenseId    = request()->post('use_license_id'); // 使用執照編號
        $buildingBuildLicenceId = request()->post('building_build_licence_id'); // 建照執照號碼

        $water     = request()->post('water'); // 水表
        $electric  = request()->post('electric'); // 電表

        $now = now();
        $infoData = [
            $this->explodeData($district, 'district') +
                $this->explodeData($building, 'building') +
                $this->explodeData($staircase, 'staircase') +
                $this->explodeData($floor, 'floor') +
                $this->explodeData($household, 'household')
        ];

        $insertData = [
            'company_id'       => crm('company_id'),
            'comid'            => crm('community_id'),
            'doorplate'        => $doorplate,
            'block_id'         => $blockId,
            'tax_id'           => $taxId,
            'public_type'      => $type,
            'main_application' => $mainApplication,
            'use_license_id'   => $useLicenseId,
            'land_use_zoning'  => $landUseZoning,
            'building_build_licence_id' => $buildingBuildLicenceId,
            'extent_of_ownership' => $extentOfOwnership,
            'locate'           => $locate,
            'created_at'       => $now,
            'updated_at'       => $now,
        ];

        $data = $infoData[0] + $insertData;
        $spaceId = $this->crmBuildingSpaceRepository->insert($data)->space_id;

        if (!empty($water)) {
            $this->createCrmHouseFeeNumber($spaceId, $water, 'water');
        }

        if (!empty($electric)) {
            $this->createCrmHouseFeeNumber($spaceId, $electric, 'electric');
        }
    }
}
