<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\Fees;

use App\Support\Abstract\DataParameter;

final class FeesData extends DataParameter implements DataInterface
{
    /**
     * 價格
     *
     * @var integer
     */
    private int $price = 0;

    /**
     * 單價
     *
     * @var integer
     */
    private int $unitPrice = 0;

    /**
     * 押金
     *
     * @var integer
     */
    private int $deposit = 0;

    /**
     * 押金月份
     *
     * @var integer
     */
    private int $depositTotalMonth = 0;

    /**
     * 管理費
     *
     * @var integer
     */
    private int $managementFee = 0;

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
        $fillable = (new Fees())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
