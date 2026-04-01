<?php

declare(strict_types=1);

namespace App\Services\Excel;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

use Maatwebsite\Excel\Facades\Excel;

use Rap2hpoutre\FastExcel\FastExcel;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Excel\UploadExcelRequest;

use App\Support\Abstract\Service;
use App\Support\Tool\Excel\Export;
use App\Support\Enum\FloorType;
use App\Support\Enum\ConfigurationType;

use App\Repositories\Space\CrmLanguageSystemRepository;
use App\Repositories\Space\CrmLanguageConfigurationRepository;

use Home\Helpers\Natsort;

final class RoleService extends Service
{
    use \App\Support\Trait\Excel\RoleExportTrait;
    use \App\Support\Trait\Excel\ExportTrait;

    public function __construct(
        private readonly CrmLanguageConfigurationRepository $crmLanguageConfigurationRepository,
        private readonly CrmLanguageSystemRepository        $crmLanguageSystemRepository
    ) {
    }

    /**
     * 空間規則Excel下載
     *
     * @return BinaryFileResponse
     */
    public function execute($identity): BinaryFileResponse
    {
        $comname = crm()->currentCommunity('comname');

        switch ($identity) {
                // 模板
            case 'example':
                $data = [];
                $fileName = $comname.'_空間名稱定義模板.xlsx';
                break;
                // 範例
            case 'template':
                $data = self::getConfigurationExampleData();
                $fileName = '空間名稱定義範例.xlsx';
                break;
                // 資料
            case 'material':
                $data = self::getConfigurationData();
                $fileName = $comname.'_空間名稱定義資料.xlsx';
                break;
        }

        $export = new Export();
        $export->condition(['規則名稱*', '名稱*', '類別*'])
            ->appendCollectionData($data)
            ->asSelectMenu(true)
            ->appendSelectMenuParameter(
                [
                    [
                        'col' => 'C',
                        'selects' => ConfigurationType::values(),
                        'count' => 300
                    ],
                ]
            );

        return Excel::download($export, $fileName);
    }

    /**
     * @param  UploadExcelRequest  $request
     *
     * @return array|null
     * @throws \OpenSpout\Common\Exception\IOException
     * @throws \OpenSpout\Common\Exception\UnsupportedTypeException
     * @throws \OpenSpout\Reader\Exception\ReaderNotOpenedException
     */
    public function import(UploadExcelRequest $request)
    {
        $typeNameMap = [
            '區'    => 'district',
            '棟'    => 'building',
            '梯'    => 'staircase',
            '樓'    => 'floor',
            '戶'    => 'privacy',
            '公設'   => 'public',
        ];

        $ruleNum = 0;

        $importedData = (new FastExcel())->import($request->file, function ($row) use ($typeNameMap) {

            if (!isset($row['類別*'])) {
                $this->fails('匯入格式錯錯誤', Response::HTTP_NOT_FOUND);
            }

            $row['類別*'] = $typeNameMap[$row['類別*']] ?? '';

            return $row;
        })->filter(fn ($type) => !empty($type['規則名稱*']))
            ->groupBy('類別*')
            ->map(function ($types) use (&$ruleNum) {
                return $types->groupBy('規則名稱*')
                    ->map(function ($languages, $languageKey) use (&$ruleNum) {
                        $ruleNum++;

                        $configData = $languages->map(function ($data, $key) {
                            return [
                                'name'  => $data['名稱*'],
                                'value' => $data['類別*'] . '.' . ($key + 1),
                                'type'  => $data['細項定義*'] ?? '',
                            ];
                        })->toArray();

                        return [
                            'ruleName'   => $languageKey,
                            'languageId' => $ruleNum,
                            'config'     => $configData,
                        ];
                    })
                    ->values()
                    ->toArray();
            });

        $this->updateConfigurationWithImportData($importedData);
    }

    /**
     * 取組態範例資料
     *
     * @return array
     */
    public function getConfigurationExampleData(): array
    {
        return [
            [
                0 => '區名',
                1 => 'A1區',
                2 => '區',
            ],
            [
                0 => '區名',
                1 => 'A2區',
                2 => '區',
            ],
            [
                0 => '棟名',
                1 => 'A棟',
                2 => '棟',
            ],
            [
                0 => '棟名',
                1 => 'B棟',
                2 => '棟',
            ],
            [
                0 => '梯名',
                1 => '1棟',
                2 => '梯',
            ],
            [
                0 => '梯名',
                1 => '2棟',
                2 => '梯',
            ],
            [
                0 => '樓名',
                1 => 'B1',
                2 => '樓',
            ],
            [
                0 => '樓名',
                1 => 'B2',
                2 => '樓',
            ],
            [
                0 => '樓名',
                1 => '1F',
                2 => '樓',
            ],
            [
                0 => '樓名',
                1 => '2F',
                2 => '樓',
            ],
            [
                0 => '戶名',
                1 => '1A',
                2 => '戶',
            ],
            [
                0 => '戶名',
                1 => '2A',
                2 => '戶',
            ],
            [
                0 => '戶名',
                1 => '3A',
                2 => '戶',
            ],
            [
                0 => '戶名',
                1 => '4A',
                2 => '戶',
            ],
            [
                0 => '戶名',
                1 => '5A',
                2 => '戶',
            ],
            [
                0 => '戶名',
                1 => '6A',
                2 => '戶',
            ],
            [
                0 => '戶名',
                1 => '會館',
                2 => '公設',
            ],
            [
                0 => '戶名',
                1 => '停車場',
                2 => '公設',
            ],
        ];
    }

    /**
     * 取組態範例資料
     *
     * @return array
     */
    public function getConfigurationData(): array
    {
        return $this->crmLanguageConfigurationRepository->find()
            ->sortBy('configuration_value')->map(
                function ($item) {
                    return [
                        $item['language'],
                        $item['configuration_name'],
                        ConfigurationType::array()[$item['configuration_type']] ?? null,
                        FloorType::array()[$item['floor_type']] ?? null,
                    ];
                }
            )->toArray();
    }

    /**
     * @param $config_list
     *
     * @return void
     * @throws \Exception
     */
    public function updateConfigurationWithImportData($config_list)
    {
        try {
            $this->judgeConfigurationRepeat($config_list);

            $condition = [
                'comid'                 => crm('community_id'),
                'company_id'            => crm('company_id'),
            ];
            //  空間組態
            $this->crmLanguageConfigurationRepository->destroyById($condition);
            $this->crmLanguageSystemRepository->delete($condition);

            $this->updateOrCreateConfiguration($config_list);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param  Collection  $configList
     *
     * @return void
     */
    public function updateOrCreateConfiguration(Collection $configList)
    {
        $configList = collect($configList)
            ->flatMap(function ($languages, $spaceKey) {
                return collect($languages)->flatMap(function ($language) use ($spaceKey) {
                    return collect($language['config'])->map(function ($config) use ($language, $spaceKey) {
                        if ($this->judgeConfigNameLength([$spaceKey => $config['name']])) {
                            throw new \Exception('資料長度過長');
                        }

                        $floorType = '';

                        $configName = $this->convertStrType($config['name'], 'TOSBC');
                        $ruleName   = $this->convertStrType($language['ruleName'], 'TOSBC');

                        $configData = [
                            'configuration_id'      => str()->uuid()->toString(),
                            'comid'                 => crm('community_id'),
                            'company_id'            => crm('company_id'),
                            'language_id'           => $language['languageId'],
                            'language'              => $ruleName,
                            'configuration_value'   => $config['value'],
                            'configuration_name'    => $configName,
                            'configuration_natsort' => Natsort::natsort_canon($configName),
                            'configuration_type'    => $spaceKey,
                        ];

                        if (in_array($spaceKey, ['floor', 'household'])) {
                            $floorTypeMap = [
                                '地上層' => 'ground',
                                '地下層' => 'underground',
                                '夾層'   => 'intermediate',
                                '突出物' => 'protrusion',
                            ];

                            $floorType = $floorTypeMap[$config['type']] ?? '';
                        }

                        $configData['floor_type'] = $floorType;

                        return $configData;
                    });
                });
            })->toArray();

        $this->crmLanguageConfigurationRepository->insert($configList);
    }

    /**
     * 判斷名稱是否重複
     *
     * @param $configList
     * @throws \Exception
     */
    public function judgeConfigurationRepeat($configList)
    {
        foreach ($configList as $languages) {
            $rule_name_repeat_map   = [];

            foreach ($languages as $language) {
                if (isset($rule_name_repeat_map[$language['ruleName']])) {
                    throw new \Exception('同空間類別下的規則名稱重複');
                } else {
                    $rule_name_repeat_map[$language['ruleName']] = 1;
                }

                foreach ($language['config'] as $config) {
                    if (!empty($config['type'])) {
                        $type = $config['type'];
                    } else {
                        $type = '';
                    }
                }
            }
        }
    }
}
