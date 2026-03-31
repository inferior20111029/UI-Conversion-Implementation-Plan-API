<?php

declare(strict_types=1);

namespace App\Services\Space\Energy;

use App\Support\Abstract\Service;

use App\Repositories\Energy\SpaceStatisticsRepository;

final class UpdateService extends Service
{
    use \App\Support\Trait\Energy\ColumnTrait;

    public function __construct(
        private readonly SpaceStatisticsRepository $spaceStatisticsRepository,
    ) {
    }

    /**
     * @param $request
     * @param  int  $id
     *
     * @return void
     */
    public function execute($request)
    {
        $this->spaceStatisticsRepository->upsert(self::fetchPatchColumnData($request));
    }
}
