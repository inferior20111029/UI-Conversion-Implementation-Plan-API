<?php

declare(strict_types=1);

namespace App\Support\Trait\Energy;

trait ColumnTrait
{
    /**
     * @param $request
     *
     * @return array
     */
    public function fetchUpdateColumnData($request): array
    {
        return [
            ...$request->all(),
            ...['created_at' => now(), 'updated_at' => now()]
            ];
    }

    /**
     * @param $request
     *
     * @return array
     */
    public function fetchPatchColumnData($request): array
    {
        return [
            ...$request->all(),
            ...['updated_at' => now()]
        ];
    }
}
