<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\NeighborhoodTransportation;

use App\Support\Abstract\DataParameter;

final class NeighborhoodTransportationData extends DataParameter implements DataInterface
{
    /**
     * 交通類型 ID
     *
     * @var integer
     */
    private int $neighborhoodTransportationId = 0;

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
        $fillable = (new NeighborhoodTransportation())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
