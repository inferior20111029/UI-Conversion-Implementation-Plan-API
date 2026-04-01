<?php

declare(strict_types=1);

namespace App\Services\Space\ParkingSpaceConfiguration;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use App\Support\Abstract\Service;

use App\Models\CrmBuildingSpaceState;
use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmParkingSpaceRepository;
use App\Repositories\Space\CrmBuildingCommonSpaceRepository;

final class UpdateService extends Service
{
    use \App\Support\Trait\Space\CrmParkingSpaceTrait;

    public function __construct(
        private readonly CrmBuildingSpaceRepository        $crmBuildingSpaceRepository,
        private readonly CrmParkingSpaceRepository         $crmParkingSpaceRepository,
        private readonly CrmBuildingSpaceState             $crmBuildingSpaceState,
        private readonly CrmBuildingCommonSpaceRepository  $crmBuildingCommonSpaceRepository,
    ) {
    }

    /**
     * 回傳車位配置編輯資料
     *
     * @return array
     */
    public function execute(string $uuid): array
    {
        $crmBuildingSpace = $this->crmParkingSpaceRepository
            ->findByUuid($uuid, crm('company_id'), crm('community_id'))
            ->toArray();

        $carSpaceDetails = collect($this->updateColumn()['car_space'])
            ->firstWhere('space_id', $crmBuildingSpace['car_space_id']) ?? [];

        $default_total_area = $carSpaceDetails['default_total_area'] ?? null;
        $total_area        = $carSpaceDetails['total_area'] ?? null;
        $rental_and_sale    = $crmBuildingSpace['house_state']['rental_and_sale'] ?? null;

        $editData = [
            ...$crmBuildingSpace,
            ...compact('default_total_area', 'total_area', 'rental_and_sale')
        ];

        return [
                'edit_data' => Arr::except($editData, ['house_state']),
            ] + $this->updateColumn();
    }

    /**
     * 更新車位配置
     *
     * @param string $id
     * @return void
     */
    public function update(string $id): void
    {
        $data = $this->postColumn();

        DB::transaction(function () use ($id, $data) {
            $this->crmParkingSpaceRepository->update($id, $data);
            $this->crmBuildingSpaceState->updateOrCreate(
                ['space_id' => $id],
                ['rental_and_sale' => (string) request()->post('rental_and_sale')]
            );
        });
    }

    private function updateColumn(): array
    {
        return self::fetchApplicationType() + self::option();
    }

    private function fetchApplicationType(): array
    {
        $buildingSpaces = $this->crmBuildingSpaceRepository->findByAll();

        $crmBuildingCommon = $this->crmBuildingCommonSpaceRepository->findByAll();

        if ($buildingSpaces->isEmpty()) {
            return [];
        }

        $carSpace = $crmBuildingCommon
            ->map(function ($space) {
                return self::fetchCrmBuildingSpace($space);
            })->values()
            ->toArray();

        $householdSpace = $buildingSpaces->whereIn('main_application', ['H001', 'H002', 'H004', 'H005', 'H006', 'H007', 'H014'])
            ->map(function ($item) {
                return [
                    'space_id'       => $item->space_id,
                    'household_name' => $item->household_name,
                ];
            })->values()
            ->toArray();

        return [
            'car_space'       => $carSpace,
            'household_space' => $householdSpace
        ];
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function configuration(string $id): int
    {
        $spaceId = request()->space_id;
        return $this->crmParkingSpaceRepository->configuration($id, $spaceId);
    }
}
