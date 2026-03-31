<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\Models\AttachedCarpark;

use App\Support\Abstract\DataParameter;

final class AttachedCarparkData extends DataParameter implements DataInterface
{
    /**
     * 類型: 機車、汽車
     *
     * @var string|null
     */
    private ?string $type = null;

    /**
     * 車位 ID
     *
     * @var string|null
     */
    private ?string $crmParkingSpaceId = null;

    /**
     * 車位價格
     *
     * @var integer
     */
    private int $price = 0;

    /**
     * 車牌號碼
     *
     * @var string|null
     */
    private ?string $licensePlateNumber = null;

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
        $fillable = (new AttachedCarpark())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
