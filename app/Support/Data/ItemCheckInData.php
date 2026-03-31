<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\ItemCheckIn;

use App\Support\Abstract\DataParameter;

final class ItemCheckInData extends DataParameter implements DataInterface
{
    /**
     * 物件 ID
     *
     * @var integer
     */
    private int $propertyId = 0;

    /**
     *  最短租期
     *
     * @var string
     */
    private ?string $checkInDate = null;

    /**
     * 最短租期類型
     *
     * @var string
     */
    private string $minimumRentalPeriodType = 'month';

    /**
     * 租期
     *
     * @var integer
     */
    private int $minimumPeriod = 0;

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
        $fillable = (new ItemCheckIn())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
