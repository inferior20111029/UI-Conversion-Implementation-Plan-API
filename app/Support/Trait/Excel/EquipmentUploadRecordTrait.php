<?php

namespace App\Support\Trait\Excel;

use App\Repositories\Equipment\CrmEquipmentUploadRecordRepository;
use App\Repositories\Equipment\CrmEquipmentRepository;

trait EquipmentUploadRecordTrait
{
    /**
     * Save file records.
     *
     * @param  int     $companyId
     * @param  int     $comid
     * @param  string  $start
     * @param  string  $end
     *
     * @return void
     */
    public function saveFile(int $companyId, int $comid, string $start, string $end)
    {
        $where = [
            'company_id' => $companyId,
            'comid'      => $comid,
            'start_time' => $start,
            'end_time'   => $end,
        ];

        $crmEquipmentRepository = new CrmEquipmentRepository();
        $crmEquipmentUploadRecordRepository = new CrmEquipmentUploadRecordRepository();

        $equipmentIds = $crmEquipmentRepository->findByExcel($where);

        $data = [];
        $currentTime = date('Y-m-d H:i:s');

        foreach ($equipmentIds as $item) {
            $id = $item->id;
            $extra = json_decode($item->extra);

            foreach ($extra as $key => $fileName) {
                if (!empty($fileName)) {
                    $explodeNames = explode(',', $fileName);

                    foreach ($explodeNames as $k => $item) {

                        $data[] = [
                            'company_id'       => $companyId,
                            'comid'            => $comid,
                            'crm_equipment_id' => $id,
                            'type_name'        => $key,
                            'show_name'        => $item ?? null,
                            'created_at'       => $currentTime,
                            'updated_at'       => $currentTime,
                        ];
                    }
                }
            }
        }

        if (!empty($data)) {
            $crmEquipmentUploadRecordRepository->insert($data);
        }
    }
}
