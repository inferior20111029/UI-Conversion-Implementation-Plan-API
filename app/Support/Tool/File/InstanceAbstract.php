<?php

declare(strict_types=1);

namespace App\Support\Tool\File;

use Exception;
use Throwable;
use Illuminate\Support\Collection;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use App\Models\File;

use App\Support\Tool\File\Enum\SupportType;
use App\Support\Tool\File\Constants\SupportMime;
use App\Support\Tool\File\Repository\FileRepository;

abstract class InstanceAbstract implements SupportMime, InstanceInterface
{
    public const IS_BASE64 = "base64";

    /**
     * instance type
     *
     * @var string
     */
    protected static string $instance;

    /**
     * file instance
     *
     * @var mixed
     */
    protected static mixed $file;

    /**
     * 檔案資料
     *
     * @var array
     */
    protected static array $fileInfo;

    /**
     * 檔案類型
     *
     * @var string
     */
    protected static string $fileType = 'file';

    /**
     * 取得的檔案目標
     *
     * @var \App\Models\File|array|null
     */
    protected static File|array|null $find = null;

    /**
     * 檔案實例
     *
     * @param mixed $file
     *
     * @throws \Exception
     *
     * @return static
     */
    public static function parse($file): static
    {
        static::verifyFile($file);
        static::setFileType($file);

        static::$fileInfo = static::info($file);

        static::$file = match (static::$fileType) {
            self::IS_BASE64 => static::parseBase64Image($file),
            default => $file
        };

        static::$instance = static::isImage($file)
            && in_array(data_get(static::$fileInfo, 'mime'), static::LIST)
            && static::$fileType !== self::IS_BASE64
            ? SupportType::IMAGE->value
            : SupportType::FILE->value;

        return app(static::class);
    }

    /**
     * 目標實例
     *
     * @param mixed $target
     *
     * @return static
     */
    public static function find($target): static
    {
        $fileRepository = new FileRepository();

        $find = $target;
        if (is_int($target) || is_numeric($target)) {
            $find = $fileRepository->findByID([(int) $find])->first();
        } elseif (is_string($target) && str($target)->isUuid()) {
            $find = $fileRepository->findByUuid([$find])->first();
        } elseif ($target instanceof Collection) {
            $find = $target->toArray();
        } elseif (is_array($target)) {
            $ids = array_map('intval', array_filter($target, 'is_numeric'));
            $firstFileData = $fileRepository->findByID($ids);

            $uuids = array_filter($target, fn (mixed $value): bool => str($value)->isUuid());
            $secondFileData = $fileRepository->findByUuid($uuids);

            $find = $firstFileData->merge($secondFileData)->toArray();
        }

        static::$find = is_array($find) || $find instanceof File ? $find : null;

        return app(static::class);
    }

    /**
     * 確認是否為檔案
     *
     * @param mixed $file
     *
     * @return boolean
     */
    public static function isFile($file): bool
    {
        try {
            return is_file((string) $file);
        } catch (Throwable) {
        }

        return false;
    }

    /**
     * 確認是否為圖片
     *
     * @param mixed $file
     *
     * @return boolean
     */
    public static function isImage($file): bool
    {
        try {
            return is_array(getimagesize((string) $file));
        } catch (Throwable) {
        }

        return false;
    }

    /**
     * 確認是否為 base64 檔案
     *
     * @param mixed $file
     *
     * @return boolean
     */
    public static function isBase64Image($base64Image): bool
    {
        try {
            $base64 = static::parseBase64Image($base64Image);

            $manager = new ImageManager(new Driver());
            $manager->read($base64);

            return true;
        } catch (Throwable) {
        }

        return false;
    }

    /**
     * 取得檔案資訊
     *
     * @param mixed $file
     *
     * @return array|null
     */
    public static function info($file): ?array
    {
        try {
            $base64 = static::parseBase64Image($file);
            return static::base64FileInfo($base64);
        } catch (Throwable) {
        }

        try {
            return static::defaultFileInfo($file);
        } catch (Throwable) {
        }

        return null;
    }

    /**
     * 設定檔案類型
     *
     * @param mixed $file
     * @return void
     */
    private static function setFileType($file): void
    {
        try {
            $base64 = static::parseBase64Image($file);

            if (!empty($base64)) {
                static::$fileType = self::IS_BASE64;
            }
        } catch (Throwable) {
        }
    }

    /**
     * 驗證是否為檔案
     *
     * @param mixed $file
     * @throws Exception
     *
     * @return void
     */
    private static function verifyFile($file): void
    {
        if (false === static::isFile($file) && false === static::isBase64Image($file)) {
            throw new Exception('This Is Not A File');
        }
    }

    /**
     * 取得檔案資訊
     *
     * @param mixed $file
     *
     * @return array
     */
    private static function defaultFileInfo($file): array
    {
        return pathinfo($file->getClientOriginalName()) + [
            'mime' => $file?->getMimeType() ?? '',
            'size' => $file?->getSize() ?? 0
        ];
    }

    /**
     * 取得 base64 檔案資訊
     *
     * @param string $base64Image
     *
     * @return array
     */
    private static function base64FileInfo(string $base64Image): array
    {
        $info = getImageSizeFromString($base64Image);
        $mime = data_get($info, 'mime');

        return [
            'extension' => last(explode('/', $mime)),
            'mime' => $mime ?? '',
            'size' => (int) data_get($info, 'bits')
        ];
    }

    /**
     * 解析 base64 圖片
     *
     * @param string $base64Image
     *
     * @return boolean|string
     */
    private static function parseBase64Image(string $base64Image): bool|string
    {
        return base64_decode(
            substr($base64Image, strpos($base64Image, ',') + 1),
            true
        );
    }
}
