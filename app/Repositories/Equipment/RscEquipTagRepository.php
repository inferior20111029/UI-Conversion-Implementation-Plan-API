<?php

namespace App\Repositories\Equipment;

use App\Models\RscEquipTags;

class RscEquipTagRepository
{
    public function find($id)
    {
        return RscEquipTags::find($id);
    }
}