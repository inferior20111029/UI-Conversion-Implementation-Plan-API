<?php

declare(strict_types=1);

namespace App\Services\File;

use App\Support\Abstract\Service;
use App\Support\Tool\File\FileMagic;

use App\Models\File;

final class DestroyService extends Service
{
    /**
     * 刪除檔案
     *
     * @param \App\Models\File $file 檔案
     *
     * @return void
     */
    public function execute(File $file): void
    {
        FileMagic::find($file)->delete();
    }
}
