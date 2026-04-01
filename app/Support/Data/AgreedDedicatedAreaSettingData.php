<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\Models\AgreedDedicatedAreaSetting;

use App\Support\Abstract\DataParameter;

final class AgreedDedicatedAreaSettingData extends DataParameter implements DataInterface
{
    /**
     * 保存面積
     *
     * @var integer
     */
    private int $preservation = 0;

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
        $fillable = (new AgreedDedicatedAreaSetting())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
