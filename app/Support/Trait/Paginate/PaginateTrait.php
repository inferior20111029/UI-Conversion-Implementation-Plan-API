<?php

namespace App\Support\Trait\Paginate;

use Illuminate\Pagination\LengthAwarePaginator;

use App\Support\Constants\Paginate;

trait PaginateTrait
{
    /**
     * 取得 response 數量限制
     *
     * @return integer
     */
    public function paginateLimit(): int
    {
        return abs(intval(request()->get('perPage') ?? Paginate::DEFAULT_LIMIT));
    }

    /**
     * 取得分頁回傳格式
     *
     * @param LengthAwarePaginator $paginate
     * @param mixed $responseData
     *
     * @return array
     */
    public function paginateResponseFormat(LengthAwarePaginator $paginate, mixed $responseData): array
    {
        $paginate->appends(request()->except('page'));

        return [
            'page' => $paginate->currentPage(),
            'perPage' => $paginate->perPage(),
            'total' => $paginate->total(),
            'lastPage' => $paginate->lastPage(),
            'nextUrl' => (string) $paginate->nextPageUrl(),
            'prevUrl' => (string) $paginate->previousPageUrl(),
            'list' => $responseData
        ];
    }
}
