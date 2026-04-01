<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\ContractPaymentCycle;

use App\Support\Abstract\DataParameter;

final class ContractPaymentCycleData extends DataParameter implements DataInterface
{
    /**
     * 合約 ID
     *
     * @var integer
     */
    private int $renterContractId = 0;

    /**
     * 週期類型：每週、每月、每年
     *
     * @var string|null
     */
    private ?string $type = null;

    /**
     * 月份
     *
     * @var integer
     */
    private int $month = 1;

    /**
     * 每週-星期
     *
     * @var integer
     */
    private int $dayOfWeek = 0;

    /**
     * 每月-日
     *
     * @var integer
     */
    private int $dayOfMonth = 0;

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
        $fillable = (new ContractPaymentCycle())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
