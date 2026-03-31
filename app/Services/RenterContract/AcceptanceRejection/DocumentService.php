<?php

declare(strict_types=1);

namespace App\Services\RenterContract\AcceptanceRejection;

use App\Support\Abstract\Service;
use App\Support\Tool\File\FileMagic;

use App\Models\RenterContract;

use App\Repositories\RenterContract\RenterInspectionReturnRepository;

final class DocumentService extends Service
{
    /**
     * @param  RenterInspectionReturnRepository  $renterInspectionReturnRepository
     */
    public function __construct(
        private readonly RenterInspectionReturnRepository  $renterInspectionReturnRepository,
    ) {
    }

    /**
     * 上傳驗收/驗退確認單
     *
     * @param  RenterContract  $contract
     *
     * @return void
     */
    public function execute(RenterContract $contract): void
    {
        $fileId = (int) FileMagic::find(request()->document)->get()?->id;
        $type = request()->type;

        $this->renterInspectionReturnRepository->update([
            'renter_contract_id' => $contract->id,
            'file_id'            => $fileId,
            'type'               => $type,
        ]);
    }
}