<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\RentItemsIncluded;

use App\Support\Abstract\DataParameter;

final class RentItemsIncludedData extends DataParameter implements DataInterface
{
    /**
     * 租金項目選項 ID
     *
     * @var integer
     */
    private int $rentItemsOptionsId = 0;

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
        $fillable = (new RentItemsIncluded())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
