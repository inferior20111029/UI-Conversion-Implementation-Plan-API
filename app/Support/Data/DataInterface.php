<?php

declare(strict_types=1);

namespace App\Support\Data;

interface DataInterface
{
    public function toColumnArray(): array;

    public function fetchColumn(): array;
}
