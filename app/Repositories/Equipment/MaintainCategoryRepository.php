<?php

namespace App\Repositories\Equipment;

use App\Models\MaintainCategory;
use Illuminate\Support\Collection;

class MaintainCategoryRepository
{
    public function find($id)
    {
        return MaintainCategory::find($id);
    }

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return MaintainCategory::where('company_id', crm('company_id'))
            ->get();
    }
}
