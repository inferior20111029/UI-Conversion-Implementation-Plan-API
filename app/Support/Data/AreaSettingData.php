<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\Models\AreaSetting;

use App\Support\Abstract\DataParameter;

final class AreaSettingData extends DataParameter implements DataInterface
{
    /**
     * 換算至小數點第幾位
     *
     * @var integer
     */
    private int $decimalPlace = 0;

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
        $fillable = (new AreaSetting())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
