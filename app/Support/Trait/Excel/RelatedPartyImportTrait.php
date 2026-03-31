<?php

namespace App\Support\Trait\Excel;

use Illuminate\Support\Collection;

trait RelatedPartyImportTrait
{
    /**
     * @param  array  $date
     * @param  Collection $propertyInfoId
     * @param  array  $clientIdentityNumber
     *
     * @return array
     */
    private function fetchTransactionInfo(array $date, Collection $propertyInfoId, array $clientIdentityNumber): array
    {
        $transactionInfo = [];

        foreach (self::fetchPropertyTitleMappings($date) as $info) {
            $transactionInfo[] = [
                'property_info_id'     => $propertyInfoId[$date[0]] ?? null,
                'space_id'             => $date[0],
                'portion_percent'      => $date[17] === '' ? 0 : $date[17],
                'portion_area'         => $date[19] === '' ? 0 : $date[19],
                'portion_square_meter' => $date[18] === '' ? 0 : $date[18],
                'client_id'            => $clientIdentityNumber[$date[20]],
                'type'                 => $date[10] == '個人' ? 1 : 0,
                'mode'                 => $info['mode'],
                'mode_sort'            => $info['mode_sort'],
                'created_at'           => now(),
                'updated_at'           => now(),
            ];
        }

        return $transactionInfo;
    }

    /**
     * @param  array  $date
     *
     * @return array
     */
    private function fetchClientHasCompany(array $date, array $client): array
    {
        return [
            'id'                     => str()->uuid()->toString(),
            'client_id'              => $client[$date[20]] ?? 0,
            'identity_number'        => $date[20] ?? null,
            'company_name'           => $date[38] ?? null,
            'company_number'         => $date[40] ?? null,
            'company_type'           => $date[37] ?? null == '公法人' ? 'public' : 'private',
            'company_representative' => $date[21] ?? null,
            'company_address'        => $date[42] ?? null,
            'company_url'            => null,
            'company_fax'            => null,
            'company_telephone'      => $date[39] ?? null,
            'created_at'             => now(),
            'updated_at'             => now(),
        ];
    }

    /**
     * @param  string  $date
     *
     * @return array
     */
    private function fetchClient(array $date): array
    {
        return [
            'company_id'        => crm('company_id'),
            'account'           => $date[24] ?? null,
            'name'              => $date[21] ?? null,
            'sex'               => $date[22] == '男性' ? 1 : 2,
            'birthday'          => null,
            'identity_number'   => $date[20] ?? null,
            'mailing_address'   => $date[27] ?? null,
            'residence_address' => $date[28] ?? null,
            'transfer_account'  => $date[33] ?? null,
            'occupation'        => $date[34] ?? null,
            'employer'          => $date[35] ?? null,
            'basic_remark'      => $date[26] ?? null,
            'occupation_remark' => $date[36] ?? null,
            'life'              => $date[23] == '歿' ? 2 : 1,
            'created_at'        => now(),
            'updated_at'        => now(),
        ];
    }

    /**
     * @param  array  $date
     *
     * @return array
     */
    private function fetchPropertyTitleMappings(array $date): array
    {
        $propertyTitles = [
            'inhabitant'         => $date[11],
            'related_main'       => $date[12],
            'related_promiser'   => $date[13],
            'related_surety'     => $date[14],
            'related_loaner'     => $date[15],
            'related_paymenter'  => $date[16],
        ];

        $propertyTitleMappings = [
            2  => 'inhabitant',
            4  => 'related_main',
            7  => 'related_promiser',
            8  => 'related_surety',
            9  => 'related_loaner',
            10 => 'related_paymenter',
        ];

        return array_reduce(array_keys($propertyTitleMappings), function($carry, $key) use ($propertyTitleMappings, $propertyTitles, $date) {
            $title = $propertyTitleMappings[$key];
            if (($propertyTitles[$title] ?? 0) === 1) {
                $carry[] = [
                    'mode'      => $title,
                    'mode_sort' => $key,
                ];
            }
            return $carry;
        }, []);
    }
}