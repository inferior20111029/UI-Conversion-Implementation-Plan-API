<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Models\CrmBuildingSpace;

use App\Repositories\Space\CrmBuildingSpaceRepository;

class AreaTemplate implements WithMultipleSheets, WithEvents
{
    use Exportable;

    public const DEFAULT_BUILDING_NAME = '棟別';

    public const DEFAULT_FLOOR_NAME = '樓層';


    public function __construct()
    {
    }

    public function sheets(): array
    {
        $spaceData = $this->fetchSpaceData();

        return [
            new AreaMain($spaceData),
            new ExclusiveArea($spaceData),
            new PublicHoldingArea($spaceData),
            new AgreedDedicatedArea($spaceData),
        ];
    }

    /**
     * Excel 資料處理
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event): void {
                $companyName = empty(crm()->currentCommunity())
                    ? crm('company_name')
                    : crm('company_name') . '-' . crm()->currentCommunity('comname');

                $event->writer->getProperties()
                    ->setTitle('template')
                    ->setSubject('匯入戶別面積-模板')
                    ->setDescription('用來快速建立戶別面積資料')
                    ->setKeywords('Ezplus 億集科技')
                    ->setCategory('Excel 模板')
                    ->setCreated(time())
                    ->setCreator(crm('username'))
                    ->setLastModifiedBy(crm('username'))
                    ->setCompany($companyName);
            }
        ];
    }

    /**
     * 取得戶別資料
     *
     * @return array
     */
    private function fetchSpaceData(): array
    {
        return (new CrmBuildingSpaceRepository())
            ->findByAll()
            ->sortByDesc('building_name')
            ->map(function (CrmBuildingSpace $space): string {
                $buildingName = empty($space->building_name)
                    ? self::DEFAULT_BUILDING_NAME
                    : $space->building_name;

                $floorName = empty($space->floor_name)
                    ? self::DEFAULT_FLOOR_NAME
                    : $space->floor_name;

                $spaceName = [
                    (string) current(explode('-', $space->space_id)),
                    (string) $buildingName,
                    (string) $floorName,
                    (string) $space->household_name
                ];

                return implode('-', $spaceName);
            })
            ->unique()
            ->flatten(1)
            ->toArray();
    }
}
