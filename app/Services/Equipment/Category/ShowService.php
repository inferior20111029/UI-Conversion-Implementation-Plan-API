<?php

declare(strict_types=1);

namespace App\Services\Equipment\Category;

use App\Support\Abstract\Service;

use App\Support\Enum\FetchMessage;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Equipment\CrmEquipmentCategoryRepository;
use App\Repositories\Equipment\CrmEquipmentRepository;

final class ShowService extends Service
{
    public function __construct(
        private readonly CrmEquipmentCategoryRepository $crmEquipmentCategoryRepository,
        private readonly CrmEquipmentRepository         $crmEquipmentRepository,
    ) {
    }

    /**
     * 回傳元件類別資料
     *
     * @return array
     */
    public function execute(): array
    {
        $categories = $this->crmEquipmentCategoryRepository->findAll()->groupBy('level');

        if ($categories->isEmpty() || is_null($levelOneCategories = $categories->get(1))) {
            return [];
        }

        $systemCounts = $this->crmEquipmentRepository->groupBySystem()
            ->pluck('count', 'system_name')
            ->toArray();

        $levelTwoCategories = $categories->get(2)?->keyBy('id') ?? collect();

        return $levelOneCategories->map(function ($category) use ($levelTwoCategories, $systemCounts) {
            return $this->mapCategoryWithBranches($category, $levelTwoCategories, $systemCounts);
        })->values()->toArray();
    }

    /**
     * @param $category
     * @param $levelTwoCategories
     * @param $systemCounts
     *
     * @return array
     */
    private function mapCategoryWithBranches($category, $levelTwoCategories, $systemCounts): array
    {
        $branches = $levelTwoCategories->filter(fn($branch) => $branch->parent === $category->id);

        $mappedBranches = $branches->map(function ($branch) use ($systemCounts) {
            return [
                'id'         => $branch->id,
                'name'       => $branch->name,
                'parent'     => $branch->parent,
                'level'      => $branch->level,
                'company_id' => $branch->company_id,
                'count'      => $systemCounts[$branch->id] ?? 0,
            ];
        });

        return [
            'id'         => $category->id,
            'name'       => $category->name,
            'parent'     => $category->parent,
            'level'      => $category->level,
            'company_id' => $category->company_id,
            'branch'     => $mappedBranches,
            'count'      => $mappedBranches->sum('count'),
        ];
    }

    /**
     * 回傳合併資料
     *
     * @return array
     */
    public function mergeInfo(): array
    {
        return $this->crmEquipmentCategoryRepository
            ->findParent()
            ->values()
            ->toArray();
    }
}