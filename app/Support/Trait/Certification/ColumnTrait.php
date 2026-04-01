<?php

declare(strict_types=1);

namespace App\Support\Trait\Certification;

trait ColumnTrait
{
    /**
     * @param $version
     *
     * @return array
     */
    public function fetchUpdateColumnData($request, $version = null): array
    {
        return [
            'name'           => $request->name,
            'type'           => $request->type,
            'space_id'       => $request->space_id,
            'application_at' => $request->application_at,
            'version'        => $version + 1,
            'enable_state'   => $request->enable_state,
        ];
    }

    /**
     * @param $request
     * @param $id
     *
     * @return array
     */
    public function fetchPatchColumnData($request, $id): array
    {
        return [
            'id'             => $id,
            'name'           => $request->name,
            'application_at' => $request->application_at,
            'enable_state'   => $request->enable_state,
            'space_id'       => $request->space_id,
        ];
    }
}
