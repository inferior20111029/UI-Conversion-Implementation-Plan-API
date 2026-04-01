<?php

declare(strict_types=1);

namespace App\Support\Tool\File;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

use App\Models\File;

use App\Support\Tool\File\Enum\SupportType;
use App\Support\Tool\File\Component\CreateData;
use App\Support\Tool\File\Component\ImageProcess;
use App\Support\Tool\File\Component\UrlComponent;

use App\Support\Tool\File\Repository\FileRepository;

final class FileMagic extends InstanceAbstract
{
    /**
     * Auto File Name
     *
     * @var string
     */
    public string $fileName;

    /**
     * Storage disk
     *
     * @var string
     */
    public string $disk = "public";

    /**
     * 檔案預設儲存路徑
     *
     * @var string
     */
    public string $path = "file";

    /**
     * 圖片品質
     *
     * @var integer
     */
    protected int $quality = 70;

    /**
     * 圖片最大寬度
     *
     * @var integer
     */
    protected int $width = 1920;

    public function __construct()
    {
        $this->fileName = (string) str()->random(40);
    }

    /**
     * 指定檔案名稱
     *
     * @param string $name
     *
     * @return static
     */
    public function fileName(string $name): static
    {
        $this->fileName = (string) $name;
        return $this;
    }

    /**
     * Storage Disk
     *
     * @param string $disk
     *
     * @return static
     */
    public function disk(string $disk): static
    {
        $this->disk = (string) $disk;
        return $this;
    }

    /**
     * 檔案儲存路徑
     *
     * @param string $path
     *
     * @return static
     */
    public function path(string $path): static
    {
        $this->path = (string) implode('/', array_filter(explode('/', $path)));
        return $this;
    }

    /**
     * 圖片品質 1-100
     *
     * @param integer $quality
     *
     * @return static
     */
    public function quality(int $quality): static
    {
        if ($quality > 100 || $quality < 1) {
            return $this;
        }

        $this->quality = (int) $quality;
        return $this;
    }

    /**
     * 圖片最大寬度
     *
     * @param integer $width
     *
     * @return static
     */
    public function width(int $width): static
    {
        $this->width = (int) $width;
        return $this;
    }

    /**
     * 取得檔案資料
     *
     * @return \App\Models\File|array|null
     */
    public function get(): File|array|null
    {
        return !empty(static::$find)
            ? static::$find
            : null;
    }

    /**
     * 儲存
     *
     * @return \App\Models\File|null
     */
    public function save(): ?File
    {
        /** @var FilesystemAdapter */
        $storage = Storage::disk($this->disk);

        $extension = (string) data_get(static::$fileInfo, 'extension');
        $mime = (string) data_get(static::$fileInfo, 'mime');
        $fullFileName = "{$this->fileName}.{$extension}";
        $savePath = "/{$this->path}/{$fullFileName}";

        // 儲存圖片
        if (SupportType::IMAGE->value === static::$instance) {
            $imageProcess = new ImageProcess(static::$file);

            $image = $imageProcess->image
                ->scaleDown(width: $this->width)
                ->encodeByMediaType($mime, quality: $this->quality);

            static::$fileInfo['size'] = $image->size();

            $storage->put($savePath, $image);
        }

        // 儲存檔案
        if (static::$instance === SupportType::FILE->value) {
            match (static::$fileType) {
                static::IS_BASE64 => $storage->put($savePath, static::$file),
                default => $storage->putFileAs($this->path, static::$file, $fullFileName)
            };
        }

        return (new CreateData())->execute($this, static::$fileInfo);
    }

    /**
     * 取得檔案連結
     *
     * @return string|array
     */
    public function url(): string|array
    {
        try {
            if (empty(static::$find)) {
                return '';
            }

            if (static::$find instanceof File) {
                $fileName = static::$find->name . '.' . static::$find->extension;
                $path = static::$find->path . '/' . $fileName;
                return (new UrlComponent())->execute(static::$find->disk, $path);
            }

            if (is_array(static::$find)) {
                return array_map(function (mixed $value): string {
                    $path = "{$value['path']}/{$value['name']}.{$value['extension']}";
                    return (new UrlComponent())->execute($value['disk'], $path);
                }, static::$find);
            }
        } catch (Exception $e) {
            Log::error($e);
        }

        return '';
    }

    /**
     * 刪除檔案
     *
     * @return boolean
     */
    public function delete(): bool
    {
        $fileRepository = new FileRepository();

        try {
            if (empty(static::$find)) {
                return false;
            }

            if (static::$find instanceof File) {
                /** @var FilesystemAdapter */
                $storage = Storage::disk(static::$find->disk);
                $fileName = static::$find->name . '.' . static::$find->extension;

                $storage->delete(static::$find->path . '/' . $fileName);
                $fileRepository->destroy([static::$find->id]);
            }

            if (is_array(static::$find)) {
                array_map(function (mixed $value): void {
                    /** @var FilesystemAdapter */
                    $storage = Storage::disk($value['disk']);
                    $storage->delete("{$value['path']}/{$value['name']}.{$value['extension']}");
                }, static::$find);

                $fileRepository->destroy(data_get(static::$find, '*.id'));
            }

            return true;
        } catch (Exception $e) {
            Log::error($e);
        }

        return false;
    }

    public function __destruct()
    {
        app()->forgetInstance(static::class);
    }
}
