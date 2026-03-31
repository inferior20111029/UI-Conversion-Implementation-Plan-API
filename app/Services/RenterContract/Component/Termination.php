<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use App\Support\Enum\TerminationState;
use App\Support\Data\RenterContractData;

use App\Http\Requests\RenterContract\TerminationRequest;

use App\Models\RenterContract;

use App\Repositories\RenterContract\RenterContractRepository;

final class Termination
{
    /**
     * @param RenterContract $contract 合約資料
     * @param \App\Http\Requests\RenterContract\TerminationRequest $request Request
     */
    public function __construct(
        private readonly RenterContract $contract,
        private readonly TerminationRequest $request
    ) {}

    /**
     * 終止合約
     *
     * @return void
     */
    public function execute(): void
    {
        $column = $this->fetchTerminationColumn($this->request->post('terminationReason'));
        $terminationContractIds = [$this->contract->id];

        if ($this->contract->mutualRenterContract->isNotEmpty()) {
            $terminationContractIds = [
                ...$terminationContractIds,
                ...$this->contract->mutualRenterContract->pluck('mutual_contract_id')->all()
            ];
        }

        (new RenterContractRepository())->termination($terminationContractIds, $column);
    }

    /**
     * 取得合約終止欄位資料
     * @param mixed $terminationReason
     * @return array
     */
    public function fetchTerminationColumn(?string $terminationReason = null): array
    {
        return (new RenterContractData(
            [
                'terminationState' => TerminationState::ALREADY->value,
                'terminationReason' => $terminationReason,
                'terminationBy' => crm('user_id'),
                'terminationAt' => now()
            ]
        ))->filterColumn()->toColumnArray();
    }
}
