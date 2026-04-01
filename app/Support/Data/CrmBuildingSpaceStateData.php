<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\CrmBuildingSpaceState;

use App\Support\Abstract\DataParameter;

final class CrmBuildingSpaceStateData extends DataParameter implements DataInterface
{
    /**
     * 居住情況
     *
     * @var string|null
     */
    private ?string $live = null;

    /**
     * 屋況
     *
     * @var string|null
     */
    private ?string $house = null;

    /**
     * 房屋租售狀態
     *
     * @var string|null
     */
    private ?string $rentalAndSale = null;

    /**
     * 銷售階段
     *
     * @var string|null
     */
    private ?string $saleStage = null;

    /**
     * 屋齡
     *
     * @var integer
     */
    private int $old = 0;

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
        $fillable = (new CrmBuildingSpaceState())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
