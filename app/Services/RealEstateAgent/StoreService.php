<?php

declare(strict_types=1);

namespace App\Services\RealEstateAgent;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;

use App\Support\Enum\CreateMessage;

use App\Http\Requests\RealEstateAgent\StoreRequest;

use App\Models\RealEstateAgent;

use App\Repositories\RealEstateAgent\RealEstateAgentRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\RealEstateAgent\ColumnTrait;

    public function __construct(
        private readonly RealEstateAgentRepository $realEstateAgentRepository
    ) {}

    /**
     * 建立房仲資料
     *
     * @param \App\Http\Requests\RealEstateAgent\StoreRequest $request Request
     *
     * @return \App\Models\RealEstateAgent
     */
    public function execute(StoreRequest $request): RealEstateAgent
    {
        $insertData = $this->fetchColumnData($request)->toColumnArray();
        $accountCreateData = $this->fetchAccountCreateColumn($request);

        return $this->create($insertData, $accountCreateData);
    }

    /**
     * 取得帳號建立欄位資料
     * @param \App\Http\Requests\RealEstateAgent\StoreRequest $request
     * @return array
     */
    private function fetchAccountCreateColumn(StoreRequest $request): array
    {
        $account = (string) $request->post('account');
        return compact('account') + $this->fetchPasswordColumn($request);
    }

    /**
     * 建立房屋仲介
     *
     * @param array $insertData 房屋仲介建立資料
     * @param array $accountCreateData 帳號建立資料
     *
     * @throws \App\Exceptions\ApiException
     *
     * @return \App\Models\RealEstateAgent
     */
    private function create(array $insertData, array $accountCreateData): RealEstateAgent
    {
        $nationalIdNumber = (string) data_get($insertData, 'national_id_number');
        $realEstateAgent = $this->realEstateAgentRepository->findByRepeatNationalIdNumber($nationalIdNumber);

        /** 如果還沒驗證的帳號採用更新來處理，預防重複建立 */
        if (!empty($realEstateAgent)) {
            $insertData['uuid'] = $realEstateAgent->uuid;
            $insertData['identification_code'] = $realEstateAgent->identification_code;
        }

        $create = $this->realEstateAgentRepository->create($insertData, $accountCreateData);

        if (!empty($create)) {
            return $create;
        }

        $this->fails(CreateMessage::FAILS->value, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
