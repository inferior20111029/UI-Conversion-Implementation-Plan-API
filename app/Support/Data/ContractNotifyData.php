<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\ContractNotify;

use App\Support\Abstract\DataParameter;

final class ContractNotifyData extends DataParameter implements DataInterface
{
    /**
     * 合約 ID
     *
     * @var integer
     */
    private int $renterContractId = 0;

    /**
     * 類型: 自行輸入、每一個月、合約結束前一個月
     *
     * @var string|null
     */
    private ?string $type = null;

    /**
     * 觸發時間
     *
     * @var string|null
     */
    private ?string $triggerTime = null;

    /**
     * 是否已觸發 0:否,1:是
     *
     * @var integer
     */
    private int $alreadyTrigger = 0;

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
        $fillable = (new ContractNotify())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
