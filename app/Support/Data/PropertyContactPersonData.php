<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\PropertyContactPerson;

use App\Support\Abstract\DataParameter;

final class PropertyContactPersonData extends DataParameter implements DataInterface
{
    /**
     * 物件 ID
     *
     * @var integer
     */
    private int $propertyId = 0;

    /**
     *  物件聯絡名稱
     *
     * @var string
     */
    private ?string $name = null;

    /**
     * 物件聯絡類型
     *
     * @var string
     */
    private string $type = 'month';

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
        $fillable = (new PropertyContactPerson())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
