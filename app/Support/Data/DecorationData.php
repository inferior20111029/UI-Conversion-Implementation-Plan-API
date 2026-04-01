<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\Decoration;

use App\Support\Abstract\DataParameter;

final class DecorationData extends DataParameter implements DataInterface
{
    /**
     * 裝潢程度
     *
     * @var string|null
     */
    private ?string $degree = null;

    /**
     * 裝潢時間
     *
     * @var string|null
     */
    private ?string $time = null;

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
        $fillable = (new Decoration())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
