<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Space;

use App\Support\Abstract\Service;

use App\Support\Tool\File\FileMagic;

use App\Models\RenterContract;
final class DocumentService extends Service
{
    /**
     * 上傳合約文檔
     *
     * @param \App\Models\RenterContract $contract 合約資料
     * @param $request
     *
     * @return void
     */
    public function execute(RenterContract $contract, $request): void
    {
        $fileId = $request->post('file_id');
        $contract->file_id = (int) FileMagic::find($fileId)->get()?->id;

        $contract->save();
    }
}