<?php

declare(strict_types=1);

namespace App\Services\File;

use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

use App\Support\Abstract\Service;

use App\Http\Requests\File\UploadRequest;

use App\Support\Tool\File\FileMagic;

final class UploadService extends Service
{
    /**
     * 上傳檔案
     *
     * @param \App\Http\Requests\File\UploadRequest $request Request
     *
     * @return array
     */
    public function execute(UploadRequest $request): array
    {
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

        if (false === $receiver->isUploaded()) {
            throw new UploadMissingFileException();
        }

        $save = $receiver->receive();

        if ($save->isFinished()) {
            $userID = empty(auth()->user()) ? (int) crm('user_id') : auth()->user()->id;

            $file = FileMagic::parse($save->getFile())
                ->disk('s3')
                ->path("leasehold/{$userID}")
                ->quality(60)
                ->save();

            return [
                'uuid' => $file->uuid
            ];
        }

        $handler = $save->handler();

        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }
}
