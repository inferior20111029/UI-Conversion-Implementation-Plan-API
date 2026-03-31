<?php

namespace App\Imports;

use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Exports\AreaMain;
use App\Exports\ExclusiveArea as ExportsExclusiveArea;
use App\Exports\PublicHoldingArea as ExportPublicHoldingArea;
use App\Exports\AgreedDedicatedArea as ExportAgreedDedicatedArea;

use App\Imports\Component\Area\Main;
use App\Imports\Component\Area\ExclusiveArea;
use App\Imports\Component\Area\PublicHoldingArea;
use App\Imports\Component\Area\AgreedDedicatedArea;

use App\Repositories\Space\CrmBuildingSpaceRepository;

class AreaTemplate implements WithMultipleSheets
{
    public function __construct()
    {
    }

    public function sheets(): array
    {
        $spaceData = $this->fetchSpaceData();
        $spaceArray = $spaceData->toArray();

        return [
            (new AreaMain($spaceArray))->title() => new Main($spaceData),
            (new ExportsExclusiveArea($spaceArray))->title() => new ExclusiveArea($spaceData),
            (new ExportPublicHoldingArea($spaceArray))->title() => new PublicHoldingArea($spaceData),
            (new ExportAgreedDedicatedArea($spaceArray))->title() => new AgreedDedicatedArea($spaceData),
        ];
    }

    /**
     * 取得戶別資料
     *
     * @return Collection
     */
    private function fetchSpaceData(): Collection
    {
        request()->merge(['perPage' => 99999999]);

        [$paginatedSpaces] = (new CrmBuildingSpaceRepository())
            ->findPrivate(crm('company_id'), crm('community_id'));

        return $paginatedSpaces->getCollection();
    }
}
