<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\Models\LandArea;

use App\Support\Abstract\DataParameter;

final class LandAreaData extends DataParameter implements DataInterface
{
    /**
     * 專用面積
     *
     * @var integer
     */
    private int $dedicated = 0;

    /**
     * 土地約定專用面積
     *
     * @var integer
     */
    private int $agreement = 0;

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
        $fillable = (new LandArea())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
