<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\ContractBank;

use App\Support\Abstract\DataParameter;

final class ContractBankData extends DataParameter implements DataInterface
{
    /**
     * 合約 ID
     *
     * @var integer
     */
    private int $renterContractId = 0;

    /**
     * 銀行類型 virtual、entity
     *
     * @var string|null
     */
    private ?string $type = null;

    /**
     * 銀行代碼
     *
     * @var string|null
     */
    private ?string $code = null;

    /**
     * 銀行帳號
     *
     * @var string|null
     */
    private ?string $account = null;

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
        $fillable = (new ContractBank())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
