<?php

declare(strict_types=1);

namespace App\Services\RealEstateAgent\Authorize;

use App\Support\Abstract\Service;

use App\Http\Requests\RealEstateAgent\MultipleDeleteAuthorizeRequest;

use App\Models\RealEstateAgentAuthorize;

use App\Repositories\RealEstateAgent\RealEstateAgentAuthorizeRepository;

final class DestroyService extends Service
{
    /**
     * 刪除房仲資料
     *
     * @param \App\Models\RealEstateAgentAuthorize $authorize 授權房仲資料
     *
     * @return void
     */
    public function execute(RealEstateAgentAuthorize $authorize): void
    {
        $authorize->delete_by = crm('user_id');
        $authorize->save();
    }

    /**
     * 批量刪除
     *
     * @param \App\Http\Requests\RealEstateAgent\MultipleDeleteAuthorizeRequest $request
     *
     * @return void
     */
    public function multiple(MultipleDeleteAuthorizeRequest $request): void
    {
        (new RealEstateAgentAuthorizeRepository())
            ->multipleDelete(
                crm('company_id'),
                crm('community_id'),
                (array) $request->post('uuids'),
                crm('user_id')
            );
    }
}
