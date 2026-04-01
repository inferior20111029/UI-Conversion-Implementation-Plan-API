<?php

declare(strict_types=1);

namespace App\Services\RealEstateAgent\Entrust;

use App\Support\Abstract\Service;
use App\Support\Tool\File\FileMagic;
use App\Support\Data\RealEstateAgentEntrustData;

use App\Http\Requests\RealEstateAgent\EntrustRequest;

use App\Models\RealEstateAgent;

use App\Repositories\RealEstateAgent\EntrustRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\Space\CheckTrait;

    public function __construct(
        private readonly EntrustRepository $entrustRepository
    ) {
    }

    /**
     * 建立委託資料
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent 房仲資料
     * @param \App\Http\Requests\RealEstateAgent\EntrustRequest $request Request
     *
     * @return void
     */
    public function execute(RealEstateAgent $realEstateAgent, EntrustRequest $request): void
    {
        $spaceId = (string) $request->post('spaceId');
        $this->spaceExists($spaceId);

        $columData = $this->fetchColumnData($realEstateAgent, $request);

        if ($this->haveEntrustData($realEstateAgent, $spaceId)) {
            $columData = $columData->excludeColumn('uuid');
        }

        $updateData = $columData->toColumnArray();
        $this->entrustRepository->updateOrCreate($updateData);
    }

    /**
     * 取得欄位資料
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent 房仲資料
     * @param \App\Http\Requests\RealEstateAgent\EntrustRequest $request Request
     *
     * @return \App\Support\Data\RealEstateAgentEntrustData
     */
    private function fetchColumnData(RealEstateAgent $realEstateAgent, EntrustRequest $request): RealEstateAgentEntrustData
    {
        $fileId = (int) FileMagic::find($request->file_id)->get()?->id;

        return new RealEstateAgentEntrustData([
            'uuid' => str()->uuid()->toString(),
            'realEstateAgentId' => $realEstateAgent->id,
            'entrustState' => $request->integer('entrustState'),
            'whileSoldOut' => $request->integer('whileSoldOut'),
            'file_id'      => $fileId ?? 0,
        ] + $request->all() + crm()->only('company_id', 'community_id'));
    }

    /**
     * 確認是否有委託資料
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent 房仲資料
     * @param string $spaceId 戶別 ID
     *
     * @return boolean
     */
    private function haveEntrustData(RealEstateAgent $realEstateAgent, string $spaceId): bool
    {
        return $realEstateAgent->entrust
            ->where('company_id', crm('company_id'))
            ->where('community_id', crm('community_id'))
            ->where('space_id', $spaceId)
            ->isNotEmpty();
    }
}
