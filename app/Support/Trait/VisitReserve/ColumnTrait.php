<?php

declare(strict_types=1);

namespace App\Support\Trait\VisitReserve;

use Illuminate\Http\Request;

use App\Support\Data\VisitReserveData;
use App\Repositories\PropertyManage\PropertyRepository;

trait ColumnTrait
{
    /**
     * 取得欄位資料
     *
     * @param \Illuminate\Http\Request $request Request
     *
     * @return \App\Support\Data\VisitReserveData
     */
    public function fetchColumnData(Request $request): VisitReserveData
    {
        $propertyUuid = (string) $request->post('propertyUuid');
        $propertyRepository = new PropertyRepository;

        $propertyId = !empty(crm()->user())
            ? (int) $propertyRepository->findByUuid(crm('company_id'), crm('community_id'), $propertyUuid)?->id
            : (int) $propertyRepository->frontendFindByUuid($propertyUuid)?->id;

        $request->merge(compact('propertyId'));

        return new VisitReserveData($request->all());
    }
}
