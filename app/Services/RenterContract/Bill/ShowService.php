<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Bill;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;

use App\Repositories\RenterContract\RenterContractRepository;

final class ShowService extends Service
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * @param RenterContractRepository $renterContractRepository
     */
    public function __construct(
        private readonly RenterContractRepository $renterContractRepository
    ) {
    }

    /**
     * 取得帳單資料
     *
     * @param integer $contractId 合約 ID
     * @param string|null $uuid 帳單 UUID
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchData(int $contractId, ?string $uuid = null): Collection
    {
        $result = str($uuid)->isUuid()
            ? $this->renterContractRepository->findBillByUuid($contractId, $uuid)
            : $this->renterContractRepository->findAllBill($contractId);

        if ($result->isNotEmpty()) {
            return $result;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }
}
