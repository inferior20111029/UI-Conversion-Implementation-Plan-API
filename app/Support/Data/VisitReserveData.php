<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\VisitReserve;

use App\Support\Abstract\DataParameter;

final class VisitReserveData extends DataParameter implements DataInterface
{
    /**
     * 租售物件管理 ID
     *
     * @var int
     */
    private int $propertyId = 0;

    /**
     * 房屋仲介 ID
     *
     * @var int
     */
    private int $realEstateAgentId = 0;

    /**
     * 預約時間
     *
     * @var string|null
     */
    private ?string $appointmentTime = null;

    /**
     * 抵達時間
     *
     * @var string|null
     */
    private ?string $arrivalTime = null;

    /**
     * 訪客人數
     *
     * @var int
     */
    private int $numberOfVisitors = 0;

    /**
     * 訪客姓名
     *
     * @var string|null
     */
    private ?string $visitorsName = null;

    /**
     * 訪客手機
     *
     * @var string|null
     */
    private ?string $visitorsCellphone = null;

    /**
     * 簽名 file_id
     *
     * @var int
     */
    private int $signature = 0;

    /**
     * 取消者 user_id
     *
     * @var int
     */
    private int $cancelBy = 0;

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
        $fillable = (new VisitReserve())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
