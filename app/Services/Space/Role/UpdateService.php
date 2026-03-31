<?php

declare(strict_types=1);

namespace App\Services\Space\Role;

use Illuminate\Support\Arr;
use App\Support\Abstract\Service;

use App\Repositories\Space\CrmLanguageSystemRepository;
use App\Repositories\Space\CrmLanguageConfigurationRepository;
use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmBuildingCommonSpaceRepository;

use Home\Helpers\Natsort;

final class UpdateService extends Service
{
    use \App\Support\Trait\Role\ColumnTrait;

    public function __construct(
        private readonly CrmLanguageConfigurationRepository $crmLanguageConfigurationRepository,
        private readonly CrmLanguageSystemRepository        $crmLanguageSystemRepository,
        private readonly CrmBuildingSpaceRepository         $crmBuildingSpaceRepository,
        private readonly CrmBuildingCommonSpaceRepository   $crmBuildingCommonSpaceRepository,
    ) {
    }

    /**
     * 回傳空間組態編輯資料
     *
     * @return array
     */
    public function execute(string $id): array
    {
        $configurationType = request()->type;

        $configuration = $this->crmLanguageConfigurationRepository->findById($configurationType, $id);
        $configurationData = $configuration->toArray();

        if (empty($configurationData)) {
            $result = [
                'language' => '',
                'language_id' => null,
                'rule_data' => []
            ];
        }

        foreach ($configurationData as $data) {
            if (!isset($data['language_id'], $data['language'])) {
                continue;
            }

            if (empty($result['language'])) {
                $result['language']    = $data['language'];
                $result['language_id'] = $data['language_id'];
            }

            $result['rule_data'][] = [
                'configuration_id'   => $data['configuration_id'],
                'configuration_name' => $data['configuration_name'],
                'configuration_type' => $data['configuration_type'],
                'floor_type'         => $data['floor_type'] ?? ''
            ];
        }

        return $result;
    }

    /**
     * 更新空間組態資料
     *
     * @param $id
     * @return void
     */
    public function updateConfiguration($id): void
    {
        $requestData = request()->post('data');
        $default     = request()->post('default');
        $language    = request()->post('language');
        $configuration  = request()->post('configuration_type');
        $companyId   = crm('company_id');
        $communityId = crm('community_id');

        if ($default) {
            $updateData = [
                'company_id'  => $companyId,
                'comid'       => $communityId,
                'language_id' => $id,
                'space_type'  => $configuration ?? null,
            ];
            $this->crmLanguageSystemRepository->updateOrCreate($updateData);
        }

        $edit  = [];
        $store = [];
        $type  = $configuration;
        $configurationType = [];

        foreach ($requestData as $key => $item) {
            $configurationValue = $configuration . '.' . ($key + 1);
            $configurationType[$configurationValue]  = $item['configuration_name'];
            $floorType  = $item['floor_type'] ?? '';

            match (isset($item['configuration_id'])) {
                true  => $edit[]   = [
                    ...$item,
                    'configuration_value' => $configurationValue,
                    'floor_type'          => $floorType ?? '',
                ],
                false => $store[]  = [
                    ...Arr::except($item, ['configuration_id']),
                    'company_id'            => $companyId,
                    'comid'                 => $communityId,
                    'language'              => $language,
                    'configuration_value'   => $configurationValue,
                    'configuration_id'      => str()->uuid()->toString(),
                    'configuration_natsort' => Natsort::natsort_canon($item['configuration_name']),
                ],
            };
        }

        if (!empty($store)) {
            $this->crmLanguageConfigurationRepository->insert($store);
        }

        if (!empty($edit)) {
            $this->crmLanguageConfigurationRepository->upsert($edit);
        }

        $this->crmLanguageConfigurationRepository->update([
            'configuration_type' => $configuration,
            'language_id'        => $id,
            'language'           => $language,
        ]);

        if($default) {
            $configurationType = $this->crmLanguageConfigurationRepository->findAll([
                'company_id'         => $companyId,
                'comid'              => $communityId,
                'language_id'        =>  $id,
                'configuration_type' => $configuration
            ])
                ->pluck('configuration_name', 'configuration_value')
                ->toArray();
        }

        if (!empty($configurationType) || $default) {
            $type = in_array($type, ['privacy', 'public']) ? 'household' : $type;

            $this->updateCrmBuildingSpace($type, $configurationType);
            $this->updateBuildingCommonSpace($type, $configurationType);
        }
    }
}
