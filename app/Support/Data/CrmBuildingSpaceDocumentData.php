<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\CrmBuildingSpaceDocument;

use App\Support\Abstract\DataParameter;

final class CrmBuildingSpaceDocumentData extends DataParameter implements DataInterface
{
    /**
     * 檔案 ID
     *
     * @var integer
     */
    private int $fileId = 0;

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
        $fillable = (new CrmBuildingSpaceDocument())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
