<?php

declare(strict_types=1);

namespace App\Support\Data;

use Carbon\Carbon;

use App\models\RenterContract;

use App\Support\Abstract\DataParameter;

final class RenterContractData extends DataParameter implements DataInterface
{
    /**
     * 承租人名字
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
     * 是否同意承租人申報 0:否,1:是
     *
     * @var int
     */
    private int $allowDeclare = 0;

    /**
     * 允許提前終止合約 0:否,1:是
     *
     * @var integer
     */
    private int $allowEarlyTermination = 0;

    /**
     * 允許轉租 0:否,1:是
     *
     * @var integer
     */
    private int $allowSublease = 0;

    /**
     * 需回復原狀 0:否,1:是
     *
     * @var integer
     */
    private int $restore = 0;

    /**
     * 備註
     *
     * @var string|null
     */
    private ?string $remark = null;

    /**
     * 簽名-file_id
     *
     * @var integer
     */
    private ?int $signature = 0;

    /**
     * 終止狀態 0:未終止,1:已終止
     *
     * @var integer
     */
    private int $terminationState = 0;

    /**
     * 終止合約原因
     *
     * @var string|null
     */
    private ?string $terminationReason = null;

    /**
     * 終止合約者 user_id
     *
     * @var integer
     */
    private int $terminationBy = 0;

    /**
     * 刪除者 user_id
     *
     * @param array $params
     */
    private int $deleteBy = 0;

    /**
     * 終止合約時間
     */
    private ?Carbon $terminationAt = null;

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
        $fillable = (new RenterContract())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
