<?php

declare(strict_types=1);

namespace App\Support\Trait\RenterContract;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Response\ApiMessage;
use App\Support\Enum\TerminationState;

use App\Models\RenterContract;

trait CheckTrait
{
    /**
     * 拋出已終止的合約
     *
     * @param \App\Models\RenterContract $contract 合約資料
     *
     * @return void
     */
    public function throwContractTermination(RenterContract $contract): void
    {
        if (TerminationState::ALREADY->value === $contract->termination_state) {
            (new ApiMessage())->throwException('此合約已終止，無法進行修改', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * 拋出共同合約
     * @param \App\Models\RenterContract $contract
     * @return void
     */
    public function throwMutualContract(RenterContract $contract)
    {
        if (!empty($contract->fromMutual)) {
            (new ApiMessage())->throwException('此合約為共同合約，故無法執行此操作', Response::HTTP_BAD_REQUEST);
        }
    }
}
