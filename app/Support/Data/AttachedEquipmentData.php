<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\Models\AttachedEquipment;

use App\Support\Abstract\DataParameter;

final class AttachedEquipmentData extends DataParameter implements DataInterface
{
    /**
     * 設備 ID
     *
     * @var integer
     */
    private int $crmEquipmentId = 0;

    /**
     * 是否顯示資訊 0:否,1:是
     *
     * @var integer
     */
    private int $displayState = 0;

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
        $fillable = (new AttachedEquipment())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
