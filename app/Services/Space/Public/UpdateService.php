<?php

declare(strict_types=1);

namespace App\Services\Space\Public;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmBuildingCommonBaseInfoRepository;

final class UpdateService extends Service
{
    use \App\Support\Trait\Public\ColumnTrait;

    public function __construct(
        private readonly CrmBuildingCommonBaseInfoRepository $crmBuildingCommonBaseInfoRepository,
    ) {
    }

    /**
     * @param $request
     * @param  int  $id
     *
     * @return void
     */
    public function execute($request, int $id): void
    {
        $this->crmBuildingCommonBaseInfoRepository->upsert(self::fetchPatchColumnData($request, $id));
    }
}
