<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\Models\PublicHoldingArea;

use App\Support\Abstract\DataParameter;

final class PublicHoldingAreaData extends DataParameter implements DataInterface
{
    /**
     * 建號
     *
     * @var string|null
     */
    private ?string $constructionNumber = null;

    /**
     * 總面積
     *
     * @var integer
     */
    private int $total = 0;

    /**
     * 權利範圍-分母
     *
     * @var int
     */
    private int $ownershipDenominator = 0;

    /**
     * 權利範圍-分子
     *
     * @var int
     */
    private int $ownershipMolecular = 0;

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
        $fillable = (new PublicHoldingArea())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
