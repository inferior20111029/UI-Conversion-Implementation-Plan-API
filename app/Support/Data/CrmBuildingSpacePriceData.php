<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\CrmBuildingSpacePrice;

use App\Support\Abstract\DataParameter;

final class CrmBuildingSpacePriceData extends DataParameter implements DataInterface
{
    /**
     * 售價
     *
     * @var integer
     */
    private int $price = 0;

    /**
     * 租金
     *
     * @var integer
     */
    private int $rentPrice = 0;

    /**
     * 訂金付款人
     *
     * @var string|null
     */
    private ?string $depositPayer = null;

    /**
     * 訂金
     *
     * @var integer
     */
    private int $deposit = 0;

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
        $fillable = (new CrmBuildingSpacePrice())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
