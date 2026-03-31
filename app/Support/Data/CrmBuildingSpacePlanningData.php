<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\CrmBuildingSpacePlanning;

use App\Support\Abstract\DataParameter;

final class CrmBuildingSpacePlanningData extends DataParameter implements DataInterface
{
    /**
     * 類型
     *
     * @var string|null
     */
    private ?string $type = null;

    /**
     * 規劃
     *
     * @var string|null
     */
    private ?string $planning = null;

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
        $fillable = (new CrmBuildingSpacePlanning())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
