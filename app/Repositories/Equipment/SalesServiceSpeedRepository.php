<?php

namespace App\Repositories\Equipment;

use App\Models\SalesServiceSpeed;

class SalesServiceSpeedRepository
{
    public function find($id)
    {
        return SalesServiceSpeed::find($id);
    }
}