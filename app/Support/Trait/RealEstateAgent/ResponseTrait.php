<?php

declare(strict_types=1);

namespace App\Support\Trait\RealEstateAgent;

use Illuminate\Support\Collection;

use App\Support\Tool\File\FileMagic;

use App\Models\RealEstateAgent;
use App\Models\RealEstateAgentEntrust;

trait ResponseTrait
{
    /**
     * 取得回傳房仲資料
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent
     *
     * @return array
     */
    public function fetchResponseRealEstateAgentData(RealEstateAgent $realEstateAgent): array
    {
        $avatar = $realEstateAgent->avatarFile;

        return $realEstateAgent->only(
            'uuid',
            'name',
            'sex',
        ) + [
            'account' => (string) $realEstateAgent?->login?->account,
            'identificationCode' => $realEstateAgent->identification_code,
            'birthday' => $realEstateAgent->birthday ?? '',
            'nationalIdNumber' => str($realEstateAgent->national_id_number)->mask('*', 3, 4),
            'cellphoneAreaCode' => $realEstateAgent->cellphone_area_code ?? '',
            'cellphone' => $realEstateAgent->cellphone ?? '',
            'contactNumbersAreaCode' => $realEstateAgent->contact_numbers_area_code ?? '',
            'contactNumbers' => $realEstateAgent->contact_numbers ?? '',
            'email' => $realEstateAgent->email ?? '',
            'companyCellphoneAreaCode' => $realEstateAgent->company_cellphone_area_code ?? '',
            'companyCellphone' => $realEstateAgent->company_cellphone ?? '',
            'companyName' => $realEstateAgent->company_name ?? '',
            'companyBranchName' => $realEstateAgent->company_branch_name ?? '',
            'companyAddress' => $realEstateAgent->company_address ?? '',
            'companyUrl' => $realEstateAgent->company_url ?? '',
            'verifyState' => (int) $realEstateAgent->verify_state,
            'avatar' => [
                'fileUuid' => $avatar?->uuid ?? '',
                'url' => FileMagic::find($avatar)->url()
            ],
            'entrust' => $this->fetchEntrustResponse($realEstateAgent->entrust)
        ];
    }

    /**
     * 取得委託的戶別資料
     *
     * @param \Illuminate\Support\Collection $entrust 委託資料
     *
     * @return Collection
     */
    public function fetchEntrustResponse(Collection $entrust): Collection
    {
        return $entrust
            ->map(function (RealEstateAgentEntrust $entrustData): array {
                $space = $entrustData->space;
                $file = $entrustData?->file;

                return [
                    'startTime' => (string) $entrustData->start_time?->toDateString(),
                    'endTime' => (string) $entrustData->end_time?->toDateString(),
                    'whileSoldOut'=> (int) $entrustData?->while_sold_out,
                    'communityName' => (string) $space->community->comname,
                    'householdName' => $space->household_name,
                    'districtName' => $space->district_name,
                    'buildingName' => $space->building_name,
                    'staircaseName' => $space->staircase_name,
                    'floorName' => $space->floor_name,
                    'avatar' => [
                        'fileUuid' => $file?->uuid ?? '',
                        'url' => FileMagic::find($file)->url()
                    ],
                ];
            });
    }
}
