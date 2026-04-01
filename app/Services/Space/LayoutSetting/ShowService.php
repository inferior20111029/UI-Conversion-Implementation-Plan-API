<?php

declare(strict_types=1);

namespace App\Services\Space\LayoutSetting;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmLayoutSettingRepository;

final class ShowService extends Service
{
    public function __construct(
        private readonly CrmLayoutSettingRepository $crmLayoutSettingRepository,
    ) {
    }

    /**
     * 回傳格局設定資料
     *
     * @return array
     */
    public function execute(): array
    {
        $name     = request()->get('name');
        $pageLess = (bool) request()->get('page_less');

        $crmParkingSpace = $this->crmLayoutSettingRepository->layoutSettingPage($name, $pageLess);

        $transformItem = fn ($item) => [
            'id'   => $item->id,
            'name' => $item->name,
        ];

        if ($pageLess) {
            return $crmParkingSpace->map($transformItem)->toArray();
        }

        $transformedList = $crmParkingSpace->getCollection()->map($transformItem);

        return $this->paginateResponseFormat($crmParkingSpace, $transformedList);
    }
}