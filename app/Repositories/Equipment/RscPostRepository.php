<?php

namespace App\Repositories\Equipment;

use App\Models\RscPost;

use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
class RscPostRepository
{
    public function find($id)
    {
        return RscPost::find($id);
    }

    /**
     * @param  array  $params
     *
     * @return Collection
     */
    public function findAll(array $params): Collection
    {
        $filters = [
            'threadid_not_equal',
            'community_stream_one_not_equal',
            'community_stream_two_not_equal',
            'company_stream_one_not_equal',
            'company_stream_two_not_equal',
        ];

        $query = RscPost::where(Arr::except($params, $filters));

        foreach ($filters as $filter) {
            if (isset($params[$filter])) {
                $column = str_replace('_not_equal', '', $filter);
                $query->where($column, '!=', '');
            }
        }
        return $query->get();
    }

    /**
     * @param  array  $data
     *
     * @return bool|null
     */
    public function create(array $data): ?RscPost
    {
        return RscPost::create($data);
    }
}