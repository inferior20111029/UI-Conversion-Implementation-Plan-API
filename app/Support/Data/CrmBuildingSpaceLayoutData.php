<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\CrmBuildingSpaceLayout;

use App\Support\Abstract\DataParameter;

final class CrmBuildingSpaceLayoutData extends DataParameter implements DataInterface
{
    /**
     * 房間定義
     *
     * @var string|null
     */
    private ?string $type = null;

    /**
     * 數量
     *
     * @var integer
     */
    private int $quantity = 0;

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
        $fillable = (new CrmBuildingSpaceLayout())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
