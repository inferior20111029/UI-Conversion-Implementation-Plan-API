<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\RenterContractCache;

use App\Support\Abstract\DataParameter;

final class RenterContractCacheData extends DataParameter implements DataInterface
{
    /**
     * 合約 ID
     *
     * @var integer
     */
    public int $renterContractId = 0;

    /**
     * 空間 id
     *
     * @var string|null
     */
    public ?string $spaceId = null;

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
        $fillable = (new RenterContractCache())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
