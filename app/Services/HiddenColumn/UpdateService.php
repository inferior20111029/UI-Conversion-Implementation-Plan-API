<?php

declare(strict_types=1);

namespace App\Services\HiddenColumn;

use App\Support\Abstract\Service;

use App\Models\Property;

use App\Repositories\HiddenColumn\HiddenColumnRepository;

final class UpdateService extends Service
{
    /**
     * @param  HiddenColumnRepository  $hiddenColumnRepository
     */
    public function __construct(
        private readonly HiddenColumnRepository $hiddenColumnRepository,
    ) {
    }

    /**
     * 修改物件資料
     *
     * @param  Property  $property
     * @param $request
     *
     * @return void
     */
    public function execute($request): void
    {
        $key = $request->get('key');
        $hiddenColumn   = $request->post('hidden_column');

        $this->hiddenColumnRepository->upsert([
            'key'        => $key,
            'value'      => json_encode($hiddenColumn),
            'user_id'    => crm('user_id'),
            'company_id' => crm('company_id'),
        ]);
    }
}