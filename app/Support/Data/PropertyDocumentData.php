<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\PropertyDocument;

use App\Support\Abstract\DataParameter;

final class PropertyDocumentData extends DataParameter implements DataInterface
{
    /**
     * 物件 ID
     *
     * @var integer
     */
    private int $propertyId = 0;

    /**
     * 檔案 ID
     *
     * @var integer
     */
    private int $fileId = 0;

    /**
     * 檔案 類型
     *
     * @var string
     */
    private string $type = 'picture';

    /**
     * @var string|array|object|null
     */
    private string|array|null|object $url = null;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        foreach ($params as $key => $value) {
            $this->{str($key)->camel()->value} = $value;
        }
    }

    public function toColumnArray(): array
    {
        $column = $this->fetchColumn();
        return $this->columnHandle($column);
    }

    public function fetchColumn(): array
    {
        $fillable = (new PropertyDocument())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
