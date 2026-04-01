<?php

namespace App\Repositories\Equipment;

use App\Models\RscEquip;

class RscEquipRepository
{
    public function find($id)
    {
        return RscEquip::find($id);
    }
}