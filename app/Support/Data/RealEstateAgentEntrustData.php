<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\Models\RealEstateAgentEntrust;

use App\Support\Abstract\DataParameter;

final class RealEstateAgentEntrustData extends DataParameter implements DataInterface
{
    /**
     * 房屋仲介 ID
     *
     * @var integer
     */
    private int $realEstateAgentId = 0;

    /**
     * 租售出為止 0:否 1:是
     *
     * @var integer
     */
    private int $whileSoldOut = 0;

    /**
     * 委託狀態 0:未委託, 1:委託
     *
     * @var integer
     */
    public int $entrustState = 1;

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
        $fillable = (new RealEstateAgentEntrust())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
