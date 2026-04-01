<?php

declare(strict_types=1);

namespace App\Support\Trait\Repository;

use Illuminate\Database\Eloquent\Builder;

trait FilterTrait
{
    /**
     * 取得 query builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }
}
