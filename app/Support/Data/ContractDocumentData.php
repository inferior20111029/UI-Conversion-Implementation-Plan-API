<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\ContractDocument;

use App\Support\Abstract\DataParameter;

final class ContractDocumentData extends DataParameter implements DataInterface
{
    /**
     * 合約 ID
     *
     * @var integer
     */
    private int $renterContractId = 0;

    /**
     * 檔案 ID
     *
     * @var integer
     */
    private int $fileId = 0;


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
        $fillable = (new ContractDocument())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
