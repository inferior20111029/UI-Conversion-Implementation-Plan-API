<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\Models\ExclusiveArea;

use App\Support\Abstract\DataParameter;

final class ExclusiveAreaData extends DataParameter implements DataInterface
{
    /**
     * 面積名稱
     *
     * @var string|null
     */
    private ?string $name = null;

    /**
     * 坪數
     *
     * @var integer
     */
    private int $ping = 0;

    /**
     * 是否列入計算 0:否,1:是
     *
     * @var integer
     */
    private int $allowCalculate = 1;

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
        $fillable = (new ExclusiveArea())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
