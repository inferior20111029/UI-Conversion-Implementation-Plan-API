<?php

declare(strict_types=1);

namespace App\Support\Tool\File\Component;

use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

class UrlComponent
{
    /**
     * 取得檔案網址
     *
     * @param string $disk
     * @param string $path
     *
     * @return string
     */
    public function execute(string $disk, string $path): string
    {
        /** @var FilesystemAdapter */
        $storage = Storage::disk($disk);

        $driver = data_get($storage->getConfig(), 'driver');

        return match ($driver) {
            "s3" => $storage->temporaryUrl($path, now()->addDay()),
            default => $storage->url($path)
        };
    }
}
