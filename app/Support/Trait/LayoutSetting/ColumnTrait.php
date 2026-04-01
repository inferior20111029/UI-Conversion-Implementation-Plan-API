<?php

namespace App\Support\Trait\LayoutSetting;

use App\Support\Enum\LayoutSetting;
use App\Support\Enum\FloorType;

trait ColumnTrait
{
    /**
     * @return array
     */
    public function fetchColumnData(): array
    {
        $type = request()->post('type');
        $floorType = $type == 1 ? request()->post('floor_type') : null;

        return [
            'name'       => request()->post('name'),
            'type'       => $type,
            'floor_type' => $floorType,
        ];
    }

    /**
     * @param  string|int  $id
     *
     * @return array
     */
    public function fetchDetailColumnData(string|int $id): array
    {
        return collect(request()->only(LayoutSetting::names()))
            ->map(fn ($value, $key) => [
                'layout_setting_id' => $id,
                'type'              => $key,
                'quantity'          => $value,
                'updated_at'        => now(),
                'created_at'        => now(),
            ])->values()
            ->toArray();
    }
}