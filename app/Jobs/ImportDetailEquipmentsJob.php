<?php

namespace App\Jobs;

use App\Repositories\Equipment\CrmEquipmentRepository;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportDetailEquipmentsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use \App\Support\Trait\Excel\CheckCategoryExistsTrait;
    use \App\Support\Trait\Excel\EquipmentUploadRecordTrait;


    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * @var mixed
     */
    private $companyId;
    /**
     * @var mixed
     */
    private $comid;
    /**
     * @var mixed
     */
    private $creater;
    /**
     * @var mixed
     */
    private $properties;
    /**
     * @var mixed
     */
    private $isEquivalent;
    /**
     * @var mixed
     */
    private $rowValues;

    public function __construct(int $companyId, int $comid, string $creater, array $rowValues)
    {
        $this->companyId    = $companyId;
        $this->comid        = $comid;
        $this->creater      = $creater;
        $this->rowValues   = $rowValues;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $crmEquipmentRepository = new CrmEquipmentRepository();

            $data = array_map([$this, 'mapCommonFields'], $this->rowValues);
            $startTime  = date('Y-m-d H:i:s');
            $crmEquipmentRepository->insertBatch($data);
            $endTime  = date('Y-m-d H:i:s');
            self::saveFile($this->companyId, $this->comid, $startTime, $endTime);

//            Log::info('Data inserted successfully.');
        } catch (\Exception $e) {
//            Log::error('An error occurred while handling the job:', [
//                'error' => $e->getMessage(),
//                'trace' => $e->getTraceAsString(),
//            ]);
        }
    }

    /**
     * @param  array  $rowValues
     *
     * @return array
     */
    private function mapCommonFields(array $rowValues): array
    {
        $typeName = trim($rowValues[0]);
        $systemName = trim($rowValues[1]);

        $typeId = $this->checkCategoryExists($this->companyId, $this->comid, $typeName, 0, 1);
        $systemId = $this->checkCategoryExists($this->companyId, $this->comid, $systemName, $typeId, 2);

        $extra = [
            'images'                     => $rowValues[30] ?: '',
            'file_bim'                   => $rowValues[31] ?: '',
            'file_shop_drawing'          => $rowValues[32] ?: '',
            'file_built_drawing'         => $rowValues[33] ?: '',
            'conservation_instructions'  => $rowValues[34] ?: '',
            'user_guide'                 => $rowValues[35] ?: '',
            'certificate_of_merchandise' => $rowValues[36] ?: '',
            'testing_report'             => $rowValues[37] ?: '',
            'other'                      => $rowValues[38] ?: '',
            'specifications'             => $rowValues[39] ?: '',
            'certification_report'       => $rowValues[40] ?: '',
            'materials_cost_list'        => $rowValues[41] ?: '',
            'floorplan'                  => $rowValues[42] ?: '',
            'building_elevation'         => $rowValues[43] ?: '',
            'lighting_scheme'            => $rowValues[44] ?: '',
            'energy_loss_estimate'       => $rowValues[45] ?: '',
        ];

        return [
            'company_id'          => $this->companyId,
            'comid'               => $this->comid,
            'poc_id'              => 'property_' . (time() + rand(0, 100000000)),
            'name'                => trim($rowValues[2]),
            'type_name'           => $typeId,
            'system_name'         => $systemId,
            'space_id'            => $rowValues[47] ?? '',
            'status'              => 1,
            'area'                => $rowValues[8] ?? '',
            'space'               => $rowValues[9] ?? '',
            'location'            => $rowValues[10] ?? '',
            'public_type'         => $rowValues[11] ?? '',
            'pcces_code'          => $rowValues[12] ?? '',
            'ominiclass_code'     => $rowValues[13] ?? '',
            'user_defined_code'   => $rowValues[14] ?? '',
            'brand'               => $rowValues[15] ?? '',
            'model'               => $rowValues[16] ?? '',
            'spec_info'           => $rowValues[17] ?? '',
            'spec'                => '',
            'size'                => $rowValues[18] ?? '',
            'weight'              => $rowValues[19] ?? '',
            'place_of_production' => $rowValues[20] ?? '',
            'unit'                => $rowValues[21] ?? '',
            'from'                => $rowValues[22] ?? '',
            'price'               => empty($rowValues[23]) ? 0 : $rowValues[23], // 預估成本
            'cost'                => empty($rowValues[24]) ? 0 : $rowValues[24], // 取得成本
            'amortization_year'   => $rowValues[25] ?? '',
            'curing_cycle'        => $rowValues[26] ?? '',
            'warranty'            => $rowValues[27] ?? '',
            'acquisition_date'    => isset($rowValues[28]) ? date('Y-m-d', strtotime($rowValues[28])) : null,
            'expiration_date'     => isset($rowValues[28]) ? date('Y-m-d', strtotime($rowValues[29])) : null,
            'properties'          => empty($rowValues[46]) ? json_encode($this->properties) : $rowValues[46],
            'extra'               => json_encode($extra),
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
            'creater'             => $this->creater,
        ];
    }
}
