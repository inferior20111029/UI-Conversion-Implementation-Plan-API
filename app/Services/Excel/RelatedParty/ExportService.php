<?php

declare(strict_types=1);

namespace App\Services\Excel\RelatedParty;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Maatwebsite\Excel\Facades\Excel;

use App\Support\Abstract\Service;
use App\Support\Tool\Excel\Export;
use App\Support\Enum\PropertyTitleType;

use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmLanguageSystemRepository;
use App\Repositories\Space\CrmLanguageConfigurationRepository;

final class ExportService extends Service
{
    use \App\Support\Trait\Excel\RelatedPartyExportTrait;
    use \App\Support\Trait\ContractingParty\ConvertDateTrait;

    public function __construct(
        private readonly CrmLanguageConfigurationRepository $crmLanguageConfigurationRepository,
        private readonly CrmLanguageSystemRepository        $crmLanguageSystemRepository,
        private readonly CrmBuildingSpaceRepository         $crmBuildingSpaceRepository,
    ) {
    }

    /**
     * Excel下載
     *
     * @return BinaryFileResponse
     */
    public function execute($identity): BinaryFileResponse
    {
        $comname = crm()->currentCommunity('comname');
        $spaceHeader = [
            '區名', '棟別', '梯間', '樓層', '戶別',
            '*建置日期', '簽約日期', '異動時間', '*變更原因', '*個人/法人', '所有權人(1:有)', '主要聯絡人(1:有)', '立約人(1:有)',
            '保證人(1:有)', '貸款人(1:有)', '繳款人(1:有)', '持分比', '持分平方公尺', '持分坪數', '*身分證字號', '*個人/負責人姓名', '個人/負責人性別',
            '存歿', '註冊APP手機號碼', '個人/負責人出生日期', '基本資訊備註(個人)', '通訊地址(個人)', '戶籍地址(個人)', '手機號碼(個人)', '市內電話(個人)',
            '電子信箱(個人)', '備用電子信箱(個人)', '可能轉帳銀行帳號(個人)', '職業(個人)', '服務機關(個人)', '職業資訊備註(個人)', '*公司類別(法人)',
            '*公司名稱(法人)', '公司電話(法人)', '*統一編號(法人)', '負責人手機號碼(法人)', '負責人電子信箱(法人)', '公司地址(法人)', '備註(法人)'
        ];

        $spaceMaterial = self::fetchSpaceMaterial();

        switch ($identity) {
                // 關係人-模板
            case 'example':
                $data = $spaceMaterial->toArray();
                $header = $spaceHeader;
                $fileName = $comname.'_關係人模板.xlsx';
                break;
                // 關係人資料
            case 'material':
                $data = self::fetchRelatedParty()->toArray();
                $header = $spaceHeader;
                $fileName = $comname.'_關係人資料.xlsx';
                break;
        }

        $export = new Export();
        $export->condition($header)
            ->appendCollectionData($data)
            ->asHorizontal(true)
            ->asVertical(true)
            ->asHeadColor(true)
            ->appendHeadColorParameter(
                [
                    ['cell' => 'F1'],
                    ['cell' => 'T1'],
                    ['cell' => 'AL1'],
                    ['cell' => 'AK1'],
                    ['cell' => 'AN1'],
                    ['cell' => 'I1'],
                    ['cell' => 'J1'],
                    ['cell' => 'U1']
                ]
            ) // 設定標題顏色
            ->asSelectMenu(true)
            ->appendSelectMenuParameter(self::fetchOption($spaceMaterial))
            ->asDateFormat(true) // 設定資料格式提醒
            ->appendDateFormatParameter(self::fetchDateFormat());

        return Excel::download($export, $fileName);
    }

    /**
     * 取得戶別資料
     *
     * @return Collection
     */
    public function fetchSpaceMaterial(): Collection
    {
        return $this->crmBuildingSpaceRepository->findByAll()
            ->map(fn ($item) => $this->mapCommonFields($item));
    }

    /**
     * @param  Collection  $selects
     *
     * @return array
     */
    private function extractColumns(Collection $selects): array
    {
        $columns = [
            'building_name',
            'district_name',
            'staircase_name',
            'floor_name',
            'household_name',
        ];

        return collect($columns)->mapWithKeys(fn ($column) => [
            $column => $this->uniqueFilter(
                $selects->pluck($column)->filter()->values()
            )->toArray(),
        ])->toArray();
    }

    /**
     *
     * @param Collection $collection
     * @return Collection
     */
    private function uniqueFilter(Collection $collection): Collection
    {
        return $collection->unique()->values();
    }
}