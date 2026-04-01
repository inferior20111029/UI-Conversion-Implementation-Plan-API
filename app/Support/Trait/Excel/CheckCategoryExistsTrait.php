<?php

namespace App\Support\Trait\Excel;

use App\Repositories\Equipment\CrmEquipmentCategoryRepository;

trait CheckCategoryExistsTrait
{
    /**
     * @param  int  $companyId
     * @param  int  $comid
     * @param  string  $name
     * @param  int  $parent
     * @param  int  $level
     *
     * @return int
     */
    public function checkCategoryExists(int $companyId, int $comid, string $name, int $parent, int $level): int
    {
        $crmEquipmentCategoryRepository = new CrmEquipmentCategoryRepository();

        $criteria = [
            'company_id' => $companyId,
            'comid'      => $comid,
            'name'       => $name,
            'parent'     => $parent,
            'level'      => $level,
        ];

        $existingCategory = $crmEquipmentCategoryRepository->findByExcel($criteria);

        if ($existingCategory->isEmpty()) {
            return $this->createCategory($companyId, $comid, $name, $parent, $level);
        }

        return $existingCategory->first()->id;
    }

    /**
     * @param  int  $companyId
     * @param  int  $comid
     * @param  string  $name
     * @param  int  $parent
     * @param  int  $level
     *
     * @return int
     */
    private function createCategory(
        int $companyId,
        int $comid,
        string $name,
        int $parent,
        int $level
    ): int {
        $crmEquipmentCategoryRepository = new CrmEquipmentCategoryRepository();

        $category = [
            'name'       => $name,
            'comid'      => $comid,
            'parent'     => $parent,
            'level'      => $level,
            'company_id' => $companyId,
        ];

        return $crmEquipmentCategoryRepository->insertGetId($category);
    }
}
