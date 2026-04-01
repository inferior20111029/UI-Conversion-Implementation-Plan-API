<?php

declare(strict_types=1);

namespace App\Services\RealEstateAgent\Entrust;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Enum\FetchMessage;
use App\Support\Abstract\Service;
use App\Support\Tool\File\FileMagic;

use App\Models\RealEstateAgent;

use App\Repositories\RealEstateAgent\RealEstateAgentRepository;

final class ShowService extends Service
{
    use \App\Support\Trait\Space\CheckTrait;
    use \App\Support\Trait\RealEstateAgent\ColumnTrait;

    /**
     * @param RealEstateAgentRepository $realEstateAgentRepository
     */
    public function __construct(
        private readonly RealEstateAgentRepository $realEstateAgentRepository
    ) {}

    /**
     * 取得委託資料
     *
     * @param string $spaceId 戶別 ID
     *
     * @return \Illuminate\Support\Collection
     */
    public function execute(string $spaceId): Collection
    {
        $this->spaceExists($spaceId);

        $agentEntrust = $this->fetchData($spaceId);
        return $this->fetchResponse($agentEntrust);
    }

    /**
     * 取得房仲委託資料
     *
     * @param string $spaceId 戶別 ID
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchData(string $spaceId): Collection
    {
        $result = $this->realEstateAgentRepository->findEntrust(crm('company_id'), crm('community_id'), $spaceId);

        if ($result->isNotEmpty()) {
            return $result;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得回傳資料
     *
     * @param \Illuminate\Support\Collection $agentEntrust 房仲委託資料
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchResponse(Collection $agentEntrust): Collection
    {
        return $agentEntrust
            ->map(function (RealEstateAgent $realEstateAgent): array {
                $entrust = $realEstateAgent->entrust->first();
                $file    = $entrust?->file;

                return [
                    'uuid' => $realEstateAgent->uuid,
                    'name' => $realEstateAgent->name,
                    'cellphoneAreaCode' => $realEstateAgent->cellphone_area_code ?? '',
                    'cellphone' => $realEstateAgent->cellphone ?? '',
                    'contactNumbersAreaCode' => $realEstateAgent->contact_numbers_area_code ?? '',
                    'contactNumbers' => $realEstateAgent->contact_numbers ?? '',
                    'email' => $realEstateAgent->email ?? '',
                    'companyCellphoneAreaCode' => $realEstateAgent->company_cellphone_area_code ?? '',
                    'companyCellphone' => $realEstateAgent->company_cellphone ?? '',
                    'companyName' => $realEstateAgent->company_name ?? '',
                    'companyAddress' => $realEstateAgent->company_address ?? '',
                    'startTime' => (string) $entrust?->start_time?->toDateString(),
                    'endTime' => (string) $entrust?->end_time?->toDateString(),
                    'whileSoldOut' => (int) $entrust?->while_sold_out,
                    'hasEntrust' => !empty($entrust),
                    'avatar' => [
                        'fileUuid' => $file?->uuid ?? '',
                        'url' => FileMagic::find($file)->url()
                    ],

                ];
            });
    }
}
