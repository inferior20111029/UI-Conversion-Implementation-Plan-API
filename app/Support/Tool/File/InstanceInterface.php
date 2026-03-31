<?php

namespace App\Support\Tool\File;

interface InstanceInterface
{
    public static function parse(mixed $file): static;

    public static function find(int|string|array $target): static;
}
