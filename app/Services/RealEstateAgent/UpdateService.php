<?php

declare(strict_types=1);

namespace App\Services\RealEstateAgent;

use App\Support\Abstract\Service;
use App\Support\Tool\File\FileMagic;
use App\Support\Data\RealEstateAgentData;

use App\Support\Enum\RequestFails;
use App\Http\Requests\RealEstateAgent\StoreRequest;

use App\Models\RealEstateAgent;

use App\Repositories\RealEstateAgent\RealEstateAgentRepository;

final class UpdateService extends Service
{
    use \App\Support\Trait\RealEstateAgent\ColumnTrait;

    /**
     * @param \App\Repositories\RealEstateAgent\RealEstateAgentRepository $realEstateAgentRepository
     */
    public function __construct(
        private readonly RealEstateAgentRepository $realEstateAgentRepository
    ) {}

    /**
     * 建立房仲資料
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent 房仲資料
     * @param \App\Http\Requests\RealEstateAgent\StoreRequest $request Request
     *
     * @return void
     */
    public function execute(RealEstateAgent $realEstateAgent, StoreRequest $request): void
    {
        $updateData = $this->filterUpdateData($this->fetchColumnData($request))
            ->excludeColumn('national_id_number')
            ->toColumnArray();

        $this->update($realEstateAgent, $updateData);
        $this->deleteOldAvatar($realEstateAgent, $updateData);
    }

    /**
     * 篩選出可更新資料
     *
     * @param \App\Support\Data\RealEstateAgentData $columnData
     *
     * @return \App\Support\Data\RealEstateAgentData
     */
    private function filterUpdateData(RealEstateAgentData $columnData): RealEstateAgentData
    {
        $only = [
            'avatar',
            'name',
            'sex',
            'birthday',
            'national_id_number',
            'cellphone_area_code',
            'cellphone',
            'contact_numbers_area_code',
            'contact_numbers',
            'email',
            'company_cellphone_area_code',
            'company_cellphone',
            'company_name',
            'company_branch_name',
            'company_address',
            'company_url'
        ];

        return $columnData->onlyColumn($only);
    }

    /**
     * 更新房屋仲介
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent 房屋仲介資料
     * @param array $updateData 房屋仲介更新資料
     *
     * @return void
     */
    private function update(RealEstateAgent $realEstateAgent, array $updateData): void
    {
        $this->realEstateAgentRepository->update($realEstateAgent->id, $updateData);
    }

    /**
     * 刪除舊的大頭像
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent 房屋仲介資料
     * @param array $updateData 房屋仲介更新資料
     *
     * @return void
     */
    private function deleteOldAvatar(RealEstateAgent $realEstateAgent, array $updateData): void
    {
        if ($realEstateAgent->avatar === data_get($updateData, 'avatar')) {
            return;
        }

        FileMagic::find($realEstateAgent->avatar)->delete();
    }
}
