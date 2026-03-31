<?php

declare(strict_types=1);

namespace App\Support\Trait\Enum;

use Illuminate\Support\Collection;

trait Convert
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function array(): array
    {
        return array_combine(self::names(), self::values());
    }

    public static function collect(): Collection
    {
        return collect(self::array());
    }

    public static function implode(string $separator = ',', string $with = 'values'): string
    {
        try {
            return implode($separator, self::{$with}());
        } catch (\Throwable) {
        }

        return '';
    }
}
