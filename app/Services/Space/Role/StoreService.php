<?php

declare(strict_types=1);

namespace App\Services\Space\Role;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmLanguageSystemRepository;
use App\Repositories\Space\CrmLanguageConfigurationRepository;

use Home\Helpers\Natsort;

final class StoreService extends Service
{
    public function __construct(
        private readonly CrmLanguageSystemRepository        $crmLanguageSystemRepository,
        private readonly CrmLanguageConfigurationRepository $crmLanguageConfigurationRepository
    ) {
    }

    public function create()
    {
        $requestData = request()->all();
        $communityId = crm('community_id');
        $companyId   = crm('company_id');
        $store = [];

        $count = $this->crmLanguageConfigurationRepository->count($requestData);

        foreach ($requestData['configuration_name'] as $key => $item) {
            $store[] = [
                'comid'                 => $communityId,
                'company_id'            => $companyId,
                'language_id'           => $requestData['language_id'] ?? null,
                'language'              => $requestData['language'],
                'configuration_value'   => $requestData['configuration_type'] . '.' . ($key + 1 + $count),
                'configuration_id'      => str()->uuid()->toString(),
                'configuration_name'    => $item,
                'configuration_natsort' => Natsort::natsort_canon($item),
                'configuration_type'    => $requestData['configuration_type'],
                'floor_type'            => $requestData['floor_type'] ?? '',
            ];
        }

        if (!empty($store)) {
            $this->crmLanguageConfigurationRepository->insert($store);
        }

        if ($requestData['default'] == 'true') {
            $updateData = [
                'comid'       => $communityId,
                'company_id'  => $companyId,
                'language_id' => $requestData['language_id'],
                'space_type'  => $requestData['configuration_type'],
            ];
            $this->crmLanguageSystemRepository->updateOrCreate($updateData);
        }
    }

    /**
     * 新增樓層
     *
     * @return void
     */
    public function createFloor()
    {
        $languageIds = request()->language_id;
        $totalFloor  = request()->total_floor;
        $configurationType = request()->configuration_type;

        $communityId = crm('community_id');
        $companyId   = crm('company_id');

        $crmLanguageConfiguration = $this->crmLanguageConfigurationRepository->findByType($configurationType);
        $groupedConfigurations = $crmLanguageConfiguration->groupBy('language_id');

        $remainingFloors = collect($languageIds)->mapWithKeys(fn ($languageId) => [
            $languageId => [
                'language' => $groupedConfigurations->get($languageId, collect())->first()->language,
                'count'    => $totalFloor - $groupedConfigurations->get($languageId, collect())->count(),
                ]
        ])->toArray();

        $configurations = collect($remainingFloors)->reduce(function ($carry, $floorDifference, $languageId) use ($totalFloor, $communityId, $companyId, $configurationType) {
            $configurations = $carry;
            $startFloor = $totalFloor - $floorDifference['count'];

            if ($floorDifference['count'] < 0) {
                $this->crmLanguageConfigurationRepository->destroyLimit($languageId, $configurationType, -$floorDifference['count']);
            }

            if ($floorDifference['count'] >= 0) {
                $newConfigurations = collect(range($startFloor + 1, $totalFloor))->map(fn ($floorNumber) => [
                    'comid'                 => $communityId,
                    'company_id'            => $companyId,
                    'language_id'           => $languageId,
                    'language'              => $floorDifference['language'],
                    'configuration_value'   => "{$configurationType}.$floorNumber",
                    'configuration_id'      => str()->uuid()->toString(),
                    'configuration_name'    => "$floorNumber 樓",
                    'configuration_natsort' => Natsort::natsort_canon("$floorNumber 樓"),
                    'configuration_type'    => $configurationType,
                    'floor_type'            => request()->floor_type ?? '',
                ])->toArray();

                $configurations = array_merge($configurations, $newConfigurations);
            }

            return $configurations;
        }, []);

        if (!empty($configurations)) {
            $this->crmLanguageConfigurationRepository->insert($configurations);
        }

//        $updateData = [
//            'comid'       => $communityId,
//            'company_id'  => $companyId,
//            'language_id' => request()->default_language_id,
//            'space_type'  => $configurationType,
//        ];
//        $this->crmLanguageSystemRepository->updateOrCreate($updateData);
    }
}
