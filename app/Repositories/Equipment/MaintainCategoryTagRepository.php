<?php

namespace App\Repositories\Equipment;

use App\Models\MaintainCategoryTags;

class MaintainCategoryTagRepository
{
    public function find($id)
    {
        return MaintainCategoryTags::find($id);
    }

    /**
     * @param  array  $data
     * @return mixed
     */
    public function create(array $data)
    {
        return MaintainCategoryTags::create($data);
    }
}