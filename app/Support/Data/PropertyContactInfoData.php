<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\PropertyContactInfo;

use App\Support\Abstract\DataParameter;

final class PropertyContactInfoData extends DataParameter implements DataInterface
{
    /**
     *
     * @var string|null
     */
    private ?string $info = null;

    /**
     * 聯絡類型
     *
     * @var string|null
     */
    private ?string $type = null;

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
        $fillable = (new PropertyContactInfo())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
