<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\Models\BillAmount;

use App\Support\Abstract\DataParameter;

final class BillAmountData extends DataParameter implements DataInterface
{
    /**
     * 合約帳單 ID
     *
     * @var integer
     */
    private int $contractBillId = 0;

    /**
     * 帳單項目
     *
     * @var string
     */
    private ?string $lineItem = null;

    /**
     * 金額
     *
     * @var integer
     */
    private int $price = 0;

    /**
     * 是否為客製 0:否,1:是
     *
     * @var integer
     */
    private int $customization = 0;

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
        $fillable = (new BillAmount())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
