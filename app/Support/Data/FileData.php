<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\Support\Abstract\DataParameter;

final class FileData extends DataParameter implements DataInterface
{
    /**
     * 檔案 UUID
     *
     * @var string|null
     */
    public ?string $uuid = null;

    /**
     * 檔案名字
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * 檔案原始名字
     *
     * @var string|null
     */
    public ?string $originalName = null;

    /**
     * 檔案副檔名
     *
     * @var string|null
     */
    public ?string $extension = null;

    /**
     * 檔案 mime
     *
     * @var string|null
     */
    public ?string $mimeType = null;

    /**
     * 檔案大小
     *
     * @var integer
     */
    public int $size = 0;

    /**
     * Storage Disk
     *
     * @var string|null
     */
    public ?string $disk = null;

    /**
     * 儲存路徑
     *
     * @var string|null
     */
    public ?string $path = null;

    /**
     * 人員類型
     *
     * @var string|null
     */
    public ?string $userType = null;

    /**
     * 建立者 user_id
     *
     * @var integer
     */
    public int $createBy = 0;

    public function toColumnArray(): array
    {
        $column = $this->fetchColumn();
        return $this->columnHandle($column);
    }

    public function fetchColumn(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'original_name' => $this->originalName,
            'extension' => $this->extension,
            'mime_type' => $this->mimeType,
            'size' => $this->size,
            'disk' => $this->disk,
            'path' => $this->path,
            'user_type' => $this->userType,
            'create_by' => $this->createBy
        ];
    }
}
