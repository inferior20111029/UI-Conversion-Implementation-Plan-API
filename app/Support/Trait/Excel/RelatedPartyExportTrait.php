<?php

namespace App\Support\Trait\Excel;

use Illuminate\Support\Collection;

trait RelatedPartyExportTrait
{
    /**
     * @param  Collection  $data
     *
     * @return array[]
     */
    private function fetchOption($data): array
    {
        $columns = self::extractColumns($data);

        return [
            $this->buildOption('A', $columns['district_name'] ?? []),
            $this->buildOption('B', $columns['building_name']),
            $this->buildOption('C', $columns['staircase_name']),
            $this->buildOption('D', $columns['floor_name']),
            $this->buildOption('E', $columns['household_name']),
            $this->buildOption('I', ['買賣', '贈予', '繼承', '分割繼承', '退戶']),
            $this->buildOption('J', ['個人', '法人']),
            $this->buildOption('V', ['女性', '男性']),
            $this->buildOption('W', ['存', '歿']),
            $this->buildOption('Ak', ['公法人', '私法人']),
        ];
    }

    /**
     * @param  string  $col
     * @param  array  $selects
     *
     * @return array
     */
    private function buildOption(string $col, array $selects): array
    {
        return [
            'col'     => $col,
            'selects' => $selects,
            'count'   => 300,
        ];
    }

    /**
     * @return array
     */
    private function fetchDateFormat(): array
    {
        $commonDateFormat = [
            'title' => '請輸入日期格式',
            'value' => 'ex:民國88年8月8日 應輸入0880808。',
            'count' => 300,
        ];

        $commonPhoneFormat = [
            'title' => '請輸入註冊手機格式',
            'value' => 'ex:0910123456,請勿號碼上加 - 以免影響資料建置',
            'count' => 300,
        ];

        return [
            [...['col' => 'F'], ...$commonDateFormat],
            [...['col' => 'G'], ...$commonDateFormat],
            [...['col' => 'H'], ...$commonDateFormat],
            [...['col' => 'Y'], ...$commonPhoneFormat],
            [...['col' => 'Z'], ...$commonDateFormat],
            [...['col' => 'AD'], ...$commonPhoneFormat],
            [...['col' => 'AP'], ...$commonPhoneFormat],
        ];
    }

    private function fetchRelatedParty(): Collection
    {
        $companyId = crm('company_id');
        $communityId = crm('community_id');

        return $this->crmBuildingSpaceRepository->fetchRelatedParty($companyId, $communityId)
            ->flatMap(function ($item) {
                if (!$item->crmPropertyInfoList) {
                    return collect();
                }

                return $item->crmPropertyInfoList->crmPropertyTransactionInfo
                    ->pluck('client_id')
                    ->unique()
                    ->map(fn ($clientId) => [
                        ...$this->mapCommonFields($item),
                        ...$this->mapPropertyInfo($item),
                        ...$this->mapPropertyTransactionInfo($item, $clientId),
                        ...$this->mapPropertyCompanyInfo($item, $clientId)
                      ]
                    );
            });
    }

    /**
     * @param $item
     *
     * @return array
     */
    private function mapCommonFields($item): array
    {
        return [
            'district_name'  => $item->district_name,
            'building_name'  => $item->building_name,
            'staircase_name' => $item->staircase_name,
            'floor_name'     => $item->floor_name,
            'household_name' => $item->household_name,
        ];
    }

    /**
     * @param $item
     *
     * @return array
     */
    private function mapPropertyInfo($item): array
    {
        return [
            'build_date'     => self::convertToRepublicDate($item->crmPropertyInfoList?->build_date),
            'sign_date'      => self::convertToRepublicDate($item->crmPropertyInfoList?->sign_date),
            'transfer_date'  => self::convertToRepublicDate($item->crmPropertyInfoList?->transfer_date),
            'transfer_cause' => $item->crmPropertyInfoList?->transfer_cause,
        ];
    }

    /**
     * @param $item
     * @param $clientId
     *
     * @return array
     */
    private function mapPropertyTransactionInfo($item, $clientId): array
    {
        if (!$item->crmPropertyInfoList) {
            return [];
        }

        $transactionInfo = $item->crmPropertyInfoList
            ->crmPropertyTransactionInfo
            ->firstWhere('client_id', $clientId);

        $modes = $transactionInfo ? $transactionInfo->pluck('mode') : collect();

        $client = $transactionInfo?->crmClient;
        $contactInfo = $client?->crmClientContact->pluck('value', 'type') ?? collect();

        return [
            'info_type'            => ['個人', '法人'][$transactionInfo?->type] ?? null,
            'inhabitant'           => $modes->contains('inhabitant') ? 1 : 0,
            'related_main'         => $modes->contains('related_main') ? 1 : 0,
            'related_promiser'     => $modes->contains('related_promiser') ? 1 : 0,
            'related_surety'       => $modes->contains('related_surety') ? 1 : 0,
            'related_loaner'       => $modes->contains('related_loaner') ? 1 : 0,
            'related_paymenter'    => $modes->contains('related_paymenter') ? 1 : 0,
            'portion_percent'      => $transactionInfo?->portion_percent,
            'portion_square_meter' => $transactionInfo?->portion_square_meter,
            'portion_area'         => $transactionInfo?->portion_area,
            'identity_number'      => $client?->identity_number,
            'name'                 => $client?->name,
            'sex'                  => $client?->sex === 1 ? '男性' : '女性',
            'life'                 => $client?->life === 1 ? '存' : '歿',
            'account'              => $client?->account,
            'birthday'             => $client?->birthday,
            'basic_remark'         => $client?->basic_remark,
            'mailing_address'      => $client?->mailing_address,
            'residence_address'    => $client?->residence_address,
            'phone'                => $contactInfo->get('phone'),
            'telephone'            => $contactInfo->get('telephone'),
            'email'                => $contactInfo->get('email'),
            'email_backup'         => $contactInfo->get('email_backup'),
            'transfer_account'     => $client?->transfer_account,
            'occupation'           => $client?->occupation,
            'employer'             => $client?->employer,
            'occupation_remark'    => $client?->occupation_remark,
        ];
    }

    /**
     * @param $item
     *
     * @return array
     */
    private function mapPropertyCompanyInfo($item, $clientId): array
    {
        $transactionInfo = $item->crmPropertyInfoList
            ->crmPropertyTransactionInfo
            ->firstWhere('client_id', $clientId);

        $company = $transactionInfo?->crmClientHasCompany;
        $client = $transactionInfo?->crmClient;
        $contactInfo = $client?->crmClientContact->pluck('value', 'type') ?? collect();

        return [
            'company_type'      => $company?->company_type === 'public' ? '公法人' : '私法人',
            'company_name'      => $company?->company_name,
            'company_telephone' => $company?->company_telephone,
            'company_number'    => $company?->company_number,
            'company_phone'     => $contactInfo->get('phone'),
            'company_email'     => $contactInfo->get('email'),
            'company_address'   => $company?->company_address,
            'company_remark'    => $client?->basic_remark,
        ];
    }
}