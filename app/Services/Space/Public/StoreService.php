<?php

declare(strict_types=1);

namespace App\Services\Space\Public;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmBuildingCommonBaseInfoRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\Public\ColumnTrait;
    public function __construct(
        private readonly CrmBuildingCommonBaseInfoRepository $crmBuildingCommonBaseInfoRepository,
    ) {
    }

    /**
     * 新增公設基本資訊
     *
     * @return void
     */
    public function execute($request): void
    {
        $this->crmBuildingCommonBaseInfoRepository->insert(self::fetchUpdateColumnData($request));
    }
}
