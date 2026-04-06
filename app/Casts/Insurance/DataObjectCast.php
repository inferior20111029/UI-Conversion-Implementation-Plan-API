<?php

namespace App\Casts\Insurance;

use App\Support\Insurance\Data\InsuranceDataObject;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

abstract class DataObjectCast implements CastsAttributes
{
    abstract protected function dataClass(): string;

    public function get(Model $model, string $key, mixed $value, array $attributes): InsuranceDataObject
    {
        $class = $this->dataClass();
        $decoded = is_string($value) ? json_decode($value, true) : $value;

        return $class::fromArray(is_array($decoded) ? $decoded : []);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        $class = $this->dataClass();

        if ($value instanceof $class) {
            return json_encode($value->toArray(), JSON_THROW_ON_ERROR);
        }

        if (is_array($value)) {
            return json_encode($class::fromArray($value)->toArray(), JSON_THROW_ON_ERROR);
        }

        throw new InvalidArgumentException(sprintf('The %s cast expects an array or %s instance.', $key, $class));
    }
}
