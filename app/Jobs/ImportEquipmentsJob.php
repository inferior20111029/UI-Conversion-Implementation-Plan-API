<?php

namespace App\Jobs;

use App\Repositories\Equipment\CrmEquipmentRepository;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportEquipmentsJob implements ShouldQueue
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
    private $item;

    public function __construct(int $companyId, int $comid, string $creater, array $item)
    {
        $this->companyId    = $companyId;
        $this->comid        = $comid;
        $this->creater      = $creater;
        $this->item         = $item;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->item[0])) {
            return;
        }

        $crmEquipmentRepository = new CrmEquipmentRepository();

        $startTime  = date('Y-m-d H:i:s');
        $typeName   = trim($this->item[0]);
        $systemName = trim($this->item[1]);

        $typeId   = $this->checkCategoryExists($this->companyId, $this->comid, $typeName, 0, 1);
        $systemId = $this->checkCategoryExists($this->companyId, $this->comid, $systemName, $typeId, 2);

        $numbers = empty($this->item[16]) ? 1 : (int)$this->item[16];
        $data    = array_fill(0, $numbers, $this->mapCommonFields($this->item, $typeId, $systemId));

        $crmEquipmentRepository->insertBatch($data);

        $endTime = date('Y-m-d H:i:s');
        self::saveFile($this->companyId, $this->comid, $startTime, $endTime);
    }

    /**
     * @param  array  $rowValues
     * @param  int  $typeId
     * @param  int  $systemId
     *
     * @return array
     */
    private function mapCommonFields(array $rowValues, int $typeId, int $systemId): array
    {
        $extra = [
            'images'             => $rowValues[26] ?: '',
            'file_bim'           => $rowValues[27] ?: '',
            'file_shop_drawing'  => $rowValues[28] ?: '',
            'file_built_drawing' => $rowValues[29] ?: '',
            'conservation_instructions'  => $rowValues[30] ?: '',
            'user_guide'                 => $rowValues[31] ?: '',
            'certificate_of_merchandise' => $rowValues[32] ?: '',
            'testing_report'             => $rowValues[33] ?: '',
            'other'                      => $rowValues[34] ?: '',
            'specifications'       => $rowValues[35] ?: '',
            'certification_report' => $rowValues[36] ?: '',
            'materials_cost_list'  => $rowValues[37] ?: '',
            'floorplan'            => $rowValues[38] ?: '',
            'building_elevation'   => $rowValues[39] ?: '',
            'lighting_scheme'      => $rowValues[40] ?: '',
            'energy_loss_estimate' => $rowValues[41] ?: '',
        ];

        return [
            // 基本資訊
            'company_id'  => $this->companyId,
            'comid'       => $this->comid,
            'poc_id'      => 'property_' . (time() + rand(0, 100000000)),
            'name'        => trim($rowValues[2]), // 設備名稱
            'type_name'   => $typeId, // 類別名稱
            'system_name' => $systemId, // 系統名稱
            'area'        => $rowValues[3], // 區域
            'space'       => $rowValues[4], // 空間
            'location'    => $rowValues[5], // 位置
            'public_type' => $rowValues[6], // 空間屬性(L=大公, S=小公, P=專有)
            'pcces_code'  => $rowValues[7], // 公共工程編碼
            'ominiclass_code'   => $rowValues[8], // OminiClass編碼
            'user_defined_code' => $rowValues[9], // 設備編碼
            'brand'       => $rowValues[10], // 品牌
            'model'       => $rowValues[11], // 型號
            'unit'        => $rowValues[17], // 單位
            'spec'        => $rowValues[12], // 補充規格資訊
            'size'        => $rowValues[13], // 尺寸
            'weight'      => $rowValues[14], // 重量
            'place_of_production' => $rowValues[15], // 產地
            'price'               => empty($rowValues[19]) ? 0 : $rowValues[19], // 預估成本
            'cost'                => empty($rowValues[20]) ? 0 : $rowValues[20], // 取得成本
            'acquisition_date'    => empty($rowValues[24]) ? null : $rowValues[24], // 取得日期
            'expiration_date'     => empty($rowValues[25]) ? null : $rowValues[25], // 保固日期
            'amortization_year' => $rowValues[21], // 使用年限
            'curing_cycle'      => $rowValues[22], // 養護週期
            'warranty'          => $rowValues[23], // 保固年限
            'extra'             => json_encode($extra),
            'properties'        => empty($rowValues[42]) ? json_encode($this->properties) : $rowValues[42], // 詳細屬性
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
            'creater'           => $this->creater,
            'status'            => 0,
        ];
    }
}
