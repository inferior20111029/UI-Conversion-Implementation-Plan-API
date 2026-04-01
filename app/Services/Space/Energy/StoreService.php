<?php

declare(strict_types=1);

namespace App\Services\Space\Energy;

use App\Support\Abstract\Service;

use App\Repositories\Energy\SpaceStatisticsRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\Energy\ColumnTrait;
    public function __construct(
        private readonly SpaceStatisticsRepository $spaceStatisticsRepository,
    ) {
    }

    /**
     * 新增認證標章資料
     *
     * @return void
     */
    public function execute($request): void
    {
        $this->spaceStatisticsRepository->insert(self::fetchUpdateColumnData($request));
    }
}
