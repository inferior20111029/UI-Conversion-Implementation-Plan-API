<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\Models\ContractAssociatedPersons;

use App\Support\Abstract\DataParameter;

final class ContractAssociatedPersonsData extends DataParameter implements DataInterface
{
    /**
     * 合約 ID
     *
     * @var integer
     */
    private int $renterContractId = 0;

    /**
     * 人員類型：保證人、同住人
     *
     * @var string|null
     */
    private ?string $type = null;

    /**
     * 姓名
     *
     * @var string|null
     */
    private ?string $name = null;

    /**
     * 身分證字號
     *
     * @var string|null
     */
    private ?string $nationalIdNumber = null;

    /**
     * 手機號碼
     *
     * @var string|null
     */
    private ?string $cellphone = null;

    /**
     * 生日
     *
     * @var string|null
     */
    private ?string $birthday = null;

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
        $fillable = (new ContractAssociatedPersons())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
