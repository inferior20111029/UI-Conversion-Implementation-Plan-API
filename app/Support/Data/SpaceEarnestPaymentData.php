<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\Models\SpaceEarnestPayment;

use App\Support\Abstract\DataParameter;

final class SpaceEarnestPaymentData extends DataParameter implements DataInterface
{
    /**
     * 付款人名字
     * @var string|null
     */
    private ?string $payer = null;

    /**
     * 金額
     * @var int
     */
    private int $amountOfMoney = 0;

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
        $fillable = (new SpaceEarnestPayment())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
