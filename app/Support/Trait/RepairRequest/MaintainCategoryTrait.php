<?php

namespace App\Support\Trait\RepairRequest;

use Illuminate\Support\Collection;
use App\Repositories\Equipment\MaintainCategoryRepository;

trait MaintainCategoryTrait
{
    /**
     * @param  int  $parentId
     *
     * @return Collection
     */
    public function transformCategories(int $parentId = 0): Collection
    {
        $categories = (new MaintainCategoryRepository())->findAll();

        return self::buildCategoryTree($categories, $parentId);
    }

    /**
     * @param  Collection  $categories
     * @param  int  $parentId
     *
     * @return Collection
     */
    private function buildCategoryTree(Collection $categories, int $parentId = 0): Collection
    {
        return $categories->filter(fn ($category) => $category['parent'] == $parentId)
            ->map(function ($category) use ($categories) {
                $node = [
                    'id'    => $category['id'],
                    'value' => $category['code'],
                    'label' => $category['name'],
                ];

                $children = self::buildCategoryTree($categories, $category['id']);
                if ($children->isNotEmpty()) {
                    $node['children'] = $children;
                }

                return $node;
            })->values();
    }
}
