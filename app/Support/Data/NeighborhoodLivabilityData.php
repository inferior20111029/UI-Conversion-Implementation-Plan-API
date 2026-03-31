<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\NeighborhoodLivability;

use App\Support\Abstract\DataParameter;

final class NeighborhoodLivabilityData extends DataParameter implements DataInterface
{
    /**
     * 租金項目選項 ID
     *
     * @var integer
     */
    private int $neighborhoodLivabilityId = 0;

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
        $fillable = (new NeighborhoodLivability())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
