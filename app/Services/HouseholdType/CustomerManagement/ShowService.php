<?php

declare(strict_types=1);

namespace App\Services\HouseholdType\CustomerManagement;

use App\Models\CrmClient;
use App\Support\Abstract\Service;
use App\Support\Enum\PropertyTitleType;
use Illuminate\Support\Collection;

use App\Repositories\HouseholdType\CrmClientRepository;
use Home\Repositories\Twibm20080519\Community\CommunityRepositoryEloquent;

final class ShowService extends Service
{
    public function __construct(
        private readonly CrmClientRepository $crmClientRepository,
        private readonly CommunityRepositoryEloquent $communityRepository,
    ) {
    }

    /**
     *  客戶總覽
     *
     */
    public function execute()
    {
        $filterKey = request()->get('filter_key', []);

        $filteredData = array_filter($filterKey, fn ($value) => !is_null($value) && $value !== '');

        $community = $this->communityRepository
            ->getCommunitys(['company_id' => crm('company_id')])
            ->pluck('comname', 'comid');

        $crmClient = $this->crmClientRepository->paginate(
            crm('company_id'),
            $filteredData,
        );

        return $this->paginateResponseFormat(
            $crmClient,
            $crmClient->getCollection()->transform(fn ($item) => $this->fetchPaginateColumnData($item, $community))
        );
    }

    /**
     * @param  CrmClient  $data
     * @param  Collection  $community
     *
     * @return array
     */
    private function fetchPaginateColumnData(CrmClient $data, Collection $community): array
    {
        $contacts = $data->crmClientContact->whereIn('type', ['phone', 'email'])->pluck('value', 'type');

        $crmClientHasCompany = $data->crmClientHasCompany->company_name ?? null;

        $salutation = $data->crmClientRelatedPerson->map(fn ($item) => [
            'space_id' => $item->space_id,
            'name'     => $item->crmClient->name . ' (' . $item->salutation . ')',
        ]);

        $mode = $data->crmPropertyTransactionInfo
            ->map(fn ($item) => [
                'community'      => $community[$item->crmBuildingSpace->comid],
                'space_id'       => $item->space_id,
                'household_name' => $item->crmBuildingSpace->household_name,
                'identity_mode'  => PropertyTitleType::array()[$item->mode],
                'build_date'     => $item->crmPropertyInfoList->build_date,
            ])
            ->groupBy('space_id')
            ->map(function ($group) use ($salutation, $crmClientHasCompany) {
                $firstItem = $group->first();

                return [
                    'community'      => $firstItem['community'],
                    'space_id'       => $firstItem['space_id'],
                    'household_name' => $firstItem['household_name'],
                    'identity_mode'  => $group->pluck('identity_mode')->all(),
                    'members'        => $salutation->where('space_id', $firstItem['space_id'])->pluck('name')->all(),
                    'property_status' => '現有戶',
                    'build_date'     => $firstItem['build_date'],
                    'company_name'   => $crmClientHasCompany,
                ];
            })
            ->values()
            ->toArray();

        return [
            'name'     => $data->name,
            'phone'    => $contacts->get('phone'),
            'email'    => $contacts->get('email'),
            'quantity' => count($mode),
            'list'     => $mode,
        ];
    }
}
