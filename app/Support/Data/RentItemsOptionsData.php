<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\RentItemsOptions;

use App\Support\Abstract\DataParameter;

final class RentItemsOptionsData extends DataParameter implements DataInterface
{
    /**
     * 項目名稱
     *
     * @var string|null
     */
    private ?string $name = null;

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
        $fillable = (new RentItemsOptions())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
