<?php

declare(strict_types=1);

namespace App\Services\VisitReserve;

use App\Support\Abstract\Service;

use App\Http\Requests\VisitReserve\StoreRequest;
use App\Http\Requests\VisitReserve\FrontendStoreRequest;

use App\Models\VisitReserve;

use App\Repositories\VisitReserve\VisitReserveRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\VisitReserve\ColumnTrait;

    /**
     * @param VisitReserveRepository $visitReserveRepository
     */
    public function __construct(
        private readonly VisitReserveRepository $visitReserveRepository
    ) {}

    /**
     * 建立房屋預約
     *
     * @param StoreRequest|FrontendStoreRequest $request Request
     *
     * @return void
     */
    public function execute(StoreRequest|FrontendStoreRequest $request): void
    {
        $insertData = $this->fetchInsertData($request);

        if (false === is_null(crm()->user())) {
            $this->visitReserveRepository->adminBackendCreate(crm('user_id'), $insertData);
        }

        if (false === is_null(auth()->user())) {
            $this->visitReserveRepository->frontendAgentCreate(auth()->user()->id, $insertData);
        }
    }

    /**
     * 取得建立資料
     *
     * @param \App\Http\Requests\VisitReserve\StoreRequest|\App\Http\Requests\VisitReserve\FrontendStoreRequest $request Request
     *
     * @return \App\Models\VisitReserve
     */
    private function fetchInsertData(StoreRequest|FrontendStoreRequest $request): VisitReserve
    {
        return new VisitReserve(
            $this->fetchColumnData($request)
                ->noHaveMacro()
                ->replace([
                    'uuid' => str()->uuid()->toString()
                ])
                ->toColumnArray()
        );
    }
}
