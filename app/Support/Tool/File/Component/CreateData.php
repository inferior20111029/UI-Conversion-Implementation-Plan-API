<?php

declare(strict_types=1);

namespace App\Support\Tool\File\Component;

use Symfony\Component\HttpFoundation\Response;

use App\Models\File;

use App\Support\Data\FileData;
use App\Support\Enum\UploadMessage;
use App\Support\Response\ApiMessage;

use App\Support\Tool\File\FileMagic;
use App\Support\Tool\File\Enum\UserType;
use App\Support\Tool\File\Repository\FileRepository;

class CreateData
{
    /**
     * 建立檔案資料
     *
     * @return \App\Models\File
     */
    public function execute(FileMagic $fileMagic, array $fileInfo): File
    {
        $insertData = $this->fetchInsertData($fileMagic, $fileInfo);

        try {
            return (new FileRepository())->create($insertData);
        } catch (\Throwable $th) {
            (new ApiMessage())->throwException(UploadMessage::FAILS->value, Response::HTTP_INTERNAL_SERVER_ERROR, $th);
        }
    }

    /**
     * 取得檔案建立資料
     *
     * @return array
     */
    public function fetchInsertData(FileMagic $fileMagic, array $fileInfo): array
    {
        $userID = empty(auth()->user())
            ? (int) crm('user_id')
            : (int) auth()->user()?->id;

        $userType = empty(auth()->user())
            ? UserType::CRM->value
            : UserType::LEASEHOLD->value;

        $fileData = new FileData();
        $fileData->uuid = str()->uuid()->toString();
        $fileData->name = $fileMagic->fileName;
        $fileData->originalName = (string) data_get($fileInfo, 'filename');
        $fileData->extension = (string) data_get($fileInfo, 'extension');
        $fileData->mimeType = (string) data_get($fileInfo, 'mime');
        $fileData->size = (int) data_get($fileInfo, 'size');
        $fileData->disk = $fileMagic->disk;
        $fileData->path = $fileMagic->path;
        $fileData->userType = $userType;
        $fileData->createBy = $userID;

        return $fileData->toColumnArray();
    }
}
