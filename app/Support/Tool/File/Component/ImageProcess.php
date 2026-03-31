<?php

declare(strict_types=1);

namespace App\Support\Tool\File\Component;

use Illuminate\Http\UploadedFile;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageProcess
{
    public Image $image;

    public function __construct(mixed $image)
    {
        $this->image = $this->readImage($image);
    }

    /**
     * 讀取圖片
     *
     * @param \Illuminate\Http\UploadedFile $image
     *
     * @return \Intervention\Image\Image
     */
    private function readImage(UploadedFile $image): Image
    {
        return (new ImageManager(Driver::class))->read($image);
    }
}
