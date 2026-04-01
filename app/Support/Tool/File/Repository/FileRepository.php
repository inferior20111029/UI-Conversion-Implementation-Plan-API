<?php

declare(strict_types=1);

namespace App\Support\Tool\File\Repository;

use Illuminate\Support\Collection;

use App\Models\File;

class FileRepository
{
    /**
     * 透過 ID 查詢檔案資料
     *
     * @param array $ids
     *
     * @return \Illuminate\Support\Collection
     */
    public function findByID(array $ids): Collection
    {
        return File::find($ids);
    }

    /**
     * 透過 UUID 查詢檔案資料
     *
     * @param array $uuids
     *
     * @return \Illuminate\Support\Collection
     */
    public function findByUuid(array $uuids): Collection
    {
        return File::whereUuid($uuids)->get();
    }

    /**
     * 建立單筆檔案資料
     *
     * @param array $insertData
     *
     * @return \App\Models\File
     */
    public function create(array $insertData): File
    {
        return File::create($insertData);
    }

    /**
     * 刪除檔案資料
     *
     * @param array $userID
     *
     * @return int
     */
    public function destroy(array $fileID): int
    {
        return File::destroy($fileID);
    }
}
