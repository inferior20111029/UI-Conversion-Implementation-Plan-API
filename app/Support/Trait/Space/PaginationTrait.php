<?php

namespace App\Support\Trait\Space;

use Illuminate\Pagination\LengthAwarePaginator;

trait PaginationTrait
{
    public static function pagination(LengthAwarePaginator $data): array
    {
        return [
            'page'     => $data->currentPage(),
            'perPage'  => $data->perPage(),
            'total'    => $data->total(),
            'lastPage' => $data->lastPage(),
            'next_url' => $data->nextPageUrl(),
            'prev_url' => $data->previousPageUrl(),
        ];
    }
}
