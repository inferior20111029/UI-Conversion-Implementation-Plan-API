<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Signature;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Data\RenterContractData;

use App\Support\Enum\Signature;
use App\Support\Enum\SignatureMessage;

use App\Models\RenterContract;

use App\Services\RenterContract\Component\InsertData;
use App\Http\Requests\RenterContract\SignatureRequest;

final class SignatureService extends Service
{
    /**
     * 合約簽名
     *
     * @param \App\Models\RenterContract $contract 合約資料
     * @param \App\Http\Requests\RenterContract\SignatureRequest $request Request
     *
     * @return void
     */
    public function execute(RenterContract $contract, SignatureRequest $request): void
    {
        $signatureBase64 = (string) $request->post('signature');

        $signature = (new InsertData($request))->fetchSignatureFileId($signatureBase64);

        $updateData = (new RenterContractData(compact('signature')))->onlyColumn('signature')->toColumnArray();
        $contract->update($updateData);
    }

    /**
     * 確認是否可以簽名
     *
     * @param \App\Models\RenterContract $contract 合約資料
     *
     * @return void
     */
    public function canSignature(RenterContract $contract): void
    {
        if ($contract->signature !== Signature::NOT_HAVE_SIGNATURE->value) {
            $this->fails(SignatureMessage::unableToRepeatTheSignature->value, Response::HTTP_BAD_REQUEST);
        }
    }
}
