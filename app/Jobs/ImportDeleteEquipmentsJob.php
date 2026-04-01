<?php

namespace App\Jobs;

use App\Repositories\Equipment\CrmEquipmentRepository;
use App\Repositories\Equipment\CrmEquipmentGroupMapRepository;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportDeleteEquipmentsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

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

    public function __construct(int $companyId, int $comid)
    {
        $this->companyId    = $companyId;
        $this->comid        = $comid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $crmEquipmentRepository = new CrmEquipmentRepository();
        $crmEquipmentGroupRepository = new CrmEquipmentGroupMapRepository();

        $crmEquipmentRepository
            ->forceDelete($this->companyId, $this->comid);

        $crmEquipmentGroupRepository
            ->forceDelete($this->companyId, $this->comid);
    }
}