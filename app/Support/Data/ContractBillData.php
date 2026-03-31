<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\ContractBill;

use App\Support\Abstract\DataParameter;

final class ContractBillData extends DataParameter implements DataInterface
{
    /**
     * 合約 ID
     *
     * @var integer
     */
    private int $renterContractId = 0;

    /**
     * 是否含稅 0:否,1:是
     *
     * @var integer
     */
    private int $includeTax = 0;

    /**
     * 已繳款 0:否,1:是
     *
     * @var integer
     */
    private int $paid = 0;

    /**
     * 刪除者 user_id
     *
     * @var integer
     */
    private int $deleteBy = 0;

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
        $fillable = (new ContractBill())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
