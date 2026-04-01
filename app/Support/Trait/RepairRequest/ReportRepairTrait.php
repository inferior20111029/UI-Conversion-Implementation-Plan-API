<?php

namespace App\Support\Trait\RepairRequest;

use Webpatser\Uuid\Uuid;

use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

use App\Support\Tool\File\FileMagic;

use App\Models\RscWorksDate;
use App\Models\RscEquip;

use App\Repositories\Equipment\RscPostRepository;
use App\Repositories\Equipment\CrmEquipmentRepository;
use App\Repositories\Equipment\MaintainCategoryTagRepository;

use Home\Repositories\DbCrm\CrmClient\CrmClientRepositoryEloquent;
use Home\Repositories\DbCrm\CrmBuildingSpace\CrmBuildingSpaceRepositoryEloquent;
use Home\Repositories\DbCrm\CrmClientHasHouse\CrmClientHasHouseRepositoryEloquent;
use Home\Repositories\DbCrm\CrmPropertyTransactionInfo\CrmPropertyTransactionInfoRepositoryEloquent;

trait ReportRepairTrait
{

    /**
     * @param  int  $id
     *
     * @return void
     * @throws \Exception
     */
    public function reportRepair(int $id)
    {
        $request = request()->only([
            'space',
            'equipment_id',
            'description',
            'space_id',
            'maintain_id',
            'type',
        ]);

        $type       = $request['type'] ?? 'privacy';
        $maintainId = implode(",", $request['maintain_id']);
        $username   = crm('username');
        $companyId  = crm('company_id');
        $comid      = crm('community_id');
        $content    = $request['description'];

        $relatedPromiser = $this->relatedPromiser($request['space_id']);

        $crmEquipmentCategoryRepository = $type === 'public'
            ? (new CrmEquipmentRepository)->findByPublicReportRepair($request['equipment_id'])
            : (new CrmEquipmentRepository)->findByReportRepair($request['equipment_id']);

        $equips = $this->formatEquipmentData($request['equipment_id'], $crmEquipmentCategoryRepository, $type);

        $communityStream = $this->getCommunityStream($comid);
        $companyStream   = $this->getCompanyStream($companyId);
        $tid             = Str::upper(Str::random(8));

        $extra = [
            'p_email'      => '', //發文者p_email
            'f_content'    => $content ?? '',
            'f_place'      => $crmEquipmentCategoryRepository->space ?? '',
            'p_gender'     => 1,
            'p_sponsor'    => crm('account') ?? 'demo',
            'f_accordance' => '',
            'f_pics'       => $this->getAnomalieFile($id, 1),
            'f_attach'     => $this->getAnomalieFile($id, 0),
            'inquiry'      => [],
            'sign'         => [],
        ];

        $title = $crmEquipmentCategoryRepository->name ?? '';

        $data = [
            'c_date'       => Carbon::now(),
            'threadid'     => $tid,
            'uuid'         => (string) Uuid::generate(4),
            'company_id'   => $companyId,
            'comid'        => $comid,
            'p_type'       => 100, //  CRM 客戶
            'p_from'       => $relatedPromiser['household_name'],
            'p_name'       => $username ?? '經理 陳得盛',
            'p_userid'     => $comid,
            'p_company_id' => $companyId,
            'f_title'      => $title,
            'f_type'       => 107, // 不動產
            'f_status'     => 1,
            'write_at'     => $username ?? '經理 陳得盛',
            'extra'        => json_encode($extra),
            'equips'       => $equips,
            'contact_user' => $relatedPromiser['name'] . '(立約人)',
            'contact_phone'=> $relatedPromiser['account'],
            'speed_code'   => null,
            'speed_name'   => null,
            'speed_processing_days'     => null,
            'except_holiday'            => null,
            'scheduled_completion_date' => null,
            'year'                 => Carbon::now()->format('Y'),
            'month'                => Carbon::now()->format('m'),
            'community_stream_one' => $communityStream[0],
            'community_stream_two' => $communityStream[1],
            'company_stream_one'   => $companyStream[0],
            'company_stream_two'   => $companyStream[1],
        ];

        $now = Carbon::now()->format('Y-m-d H:i:s');

        $rscPost = (new RscPostRepository())->create($data);

        (new MaintainCategoryTagRepository())->create([
            'company_id'            => $companyId,
            'rsc_id'                => $rscPost->id,
            'maintain_category_ids' => $maintainId,
            'created_at'            => $now,
        ]);

        $work = $rscPost->rscWorks()->create([
            'comid'                => $comid,
            'rsc_id'               => $rscPost->id,
            'belong'               => $companyId,
            'assigned_company_id'  => $companyId,
            'role'                 => 9,
            'leader'               => 'demo',
            'workers'              => '[]',
            'version'              => 2,
            'remark'               => '',
            'c_date'               => $now,
            'u_date'               => $now,
            'sync_time'            => $now,
        ]);

        RscWorksDate::create([
            'comid'                => $comid,
            'rsc_id'               => $rscPost->id,
            'wid'                  => $work->id,
            'belong'               => $companyId,
            'assigned_company_id'  => $companyId,
            'role'                 => 9,
            'date'                 => Carbon::now()->format('Y-m-d'),
            'start_time'           => '00:00:00',
            'end_time'             => '00:00:00',
            'sync_time'            => $now,
        ]);

        //rsc_equip
        if (!empty($request['equipment_id'])) {
            $pics = json_encode([]);

            $rscEquip = RscEquip::create([
                'rsc_id'   => $rscPost->id,
                'comid'    => $comid,
                'e_seq'    => $request['equipment_id'],
                'type'     => 2,
                'e_desc'   => $content ?? '',
                'e_pics'   => $pics,
                'c_date'   => Carbon::now(),
                'sync_time'=> Carbon::now(),
            ]);

            $rscEquip->rscEquipTags()->create([
                'company_id'            => $companyId,
                'maintain_category_ids' => $maintainId,
                'created_at'            => Carbon::now(),
            ]);
        }

        $this->repairRecordRepository->updateRepair($id, $rscPost->id);
    }

    /**
     * 取得單筆立約人
     *
     */
    private function relatedPromiser(string $spaceId)
    {
        $crmBuildingSpaceRepository  = new CrmBuildingSpaceRepositoryEloquent;
        $crmClientHasHouseRepository = new CrmClientHasHouseRepositoryEloquent;
        $crmClientRepository         = new CrmClientRepositoryEloquent;
        $crmPropertyTransactionInfoRepository = new CrmPropertyTransactionInfoRepositoryEloquent;

        $crmBuilding = $crmBuildingSpaceRepository->getCrmBuildingSpace($spaceId);

        $criteria = [
            'space_id'            => $spaceId,
            'registration_status' => 'registered'
        ];

        $clientIds = $crmClientHasHouseRepository->getCrmClientHasHouses($criteria)
            ->pluck('client_id')->toArray();

        $criteria = [
            'space_id'   => $spaceId,
            'mode'       => 'related.promiser',
            'client_ids' => $clientIds,
        ];

        $crmPropertyTransactionInfo = $crmPropertyTransactionInfoRepository
            ->getCrmPropertyTransactionInfos($criteria)->first();

        $clientId  = $crmPropertyTransactionInfo->client_id ?? '';
        $crmClient = $crmClientRepository->getCrmClient($clientId);

        return [
            'account'        => $crmClient['account'] ?? '',
            'name'           => $crmClient['name'] ?? '',
            'household_name' => $crmBuilding['household_name'] ?? '',
        ];
    }

    /**
     * @param  int  $comid
     *
     * @return array
     */
    public function getCommunityStream(int $comid)
    {
        return $this->calculateStream(['comid' => $comid], 'community_stream_one_not_equal', 'community_stream_two_not_equal');
    }

    /**
     * @param  int  $companyId
     *
     * @return array
     */
    public function getCompanyStream(int $companyId): array
    {
        return $this->calculateStream(['company_id' => $companyId], 'company_stream_one_not_equal', 'company_stream_two_not_equal');
    }

    /**
     * @param  array  $baseCriteria
     * @param  string  $streamOneNotEqual
     * @param  string  $streamTwoNotEqual
     *
     * @return array
     */
    private function calculateStream(array $baseCriteria, string $streamOneNotEqual, string $streamTwoNotEqual): array
    {
        $rscPostRepository = new RscPostRepository;
        $currentYear       = Carbon::now()->format('y');
        $currentMonth      = Carbon::now()->format('m');

        $criteria = [
            ...$baseCriteria,
            ...[
                'threadid_not_equal' => true,
                'year'               => $currentYear,
                $streamOneNotEqual   => true,
            ]
        ];

        $yearCount = $rscPostRepository->findAll($criteria)->count();

        $streamOne = $yearCount + 1;

        $criteria = array_merge($baseCriteria, [
            'threadid_not_equal' => true,
            'year'               => $currentYear,
            'month'              => $currentMonth,
            $streamTwoNotEqual   => true,
        ]);
        $yearMonthCount = $rscPostRepository->findAll($criteria)->count();
        $streamTwo      = $yearMonthCount + 1;

        return [$streamOne, $streamTwo];
    }

    /**
     * @param  int  $id
     * @param  int  $type
     *
     * @return Collection
     */
    private function getAnomalieFile(int $id, int $type): Collection
    {
        return $this->repairRecordFileRepository
            ->findById($id)
            ->where('type', $type)
            ->filter(fn ($record) => !empty($record->avatarFile))
            ->map(fn ($record) => FileMagic::find($record->avatarFile)->url());
    }

    /**
     * @param $equipmentId
     * @param $repository
     * @param $type
     *
     * @return string
     */
    private function formatEquipmentData($equipmentId, $repository, $type): string
    {
        if (empty($equipmentId) || $repository === null) {
            return json_encode('[]');
        }

        $spaceData = $type === 'public'
            ? $repository->crmBuildingCommonSpace
            : $repository->crmBuildingSpace;

        $equipmentData = [
            'seq'         => $equipmentId,
            'cls_name'    => $repository?->crmTypeName->name ?? '',
            'sys_name'    => $repository?->crmTypeName->name ?? '',
            'buildno'     => $spaceData?->building_name ?? '',
            'district'    => $spaceData?->district_name ?? '',
            'staircase'   => $spaceData?->staircase_name ?? '',
            'floor'       => $spaceData?->floor_name ?? '',
            'household_id'=> $spaceData?->household_name ?? '',
        ];

        return json_encode(json_encode([$equipmentData + $repository->toArray()]));
    }
}