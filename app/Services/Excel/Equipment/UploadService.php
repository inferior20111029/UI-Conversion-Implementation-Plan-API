<?php

declare(strict_types=1);

namespace App\Services\Excel\Equipment;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

use Archive7z\Archive7z;
use Archive7z\Entry;

use App\Support\Abstract\Service;
use App\Support\Tool\File\FileMagic;
use App\Support\Enum\ComponentFilesType;
use App\Repositories\Equipment\CrmEquipmentUploadRecordRepository;

final class UploadService extends Service
{
    public function __construct(
        private readonly CrmEquipmentUploadRecordRepository $crmEquipmentUploadRecordRepository,
    ) {
    }

    /**
     * @param $zip
     * @return array
     * @throws \Archive7z\Exception
     */
    public function handleZipUpload($zip)
    {
        $zipName = md5($zip->getClientOriginalName() . time()) . '.zip';
        $tempPath = 'equipments/temp';
        Storage::putFileAs($tempPath, $zip, $zipName);

        $where = [
            'company_id' => crm('company_id'),
            'comid'      => crm('community_id'),
            'avatar'     => 0,
        ];

        $uploadRecord = $this->crmEquipmentUploadRecordRepository->findByExcel($where);

        $zipFilePath = Storage::path($tempPath . '/' . $zipName);
        $obj         = new Archive7z($zipFilePath);
        $upsertData  = [];

        DB::connection('mysql_utf8')->beginTransaction();

        try {
            foreach ($obj->getEntries() as $entry) {
                $fileName = $entry->getPath();

                // 排除 macOS 系统文件
                if (strpos($fileName, '__MACOSX') !== false) {
                    continue;
                }

                $pathInfo = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (empty($pathInfo)) {
                    continue;
                }

                $checkFileMapping = $uploadRecord->where('show_name', $fileName);

                if ($checkFileMapping->isEmpty()) {
                    continue;
                }

                [$tempFilePath, $uploadedFile] = self::processUploadedFile($entry, $fileName, $pathInfo, $tempPath);

                foreach ($checkFileMapping as $item) {
                    $typeName = $item->type_name;
                    self::validateRow($typeName, $pathInfo, $entry);

                    $avatar = self::processFileEntry($uploadedFile);
                    $upsertData[] = [
                        'company_id' => crm('company_id'),
                        'comid'      => crm('community_id'),
                        'id'               => $item->id,
                        'crm_equipment_id' => $item->crm_equipment_id,
                        'avatar'           => $avatar,
                    ];
                }

                Storage::delete($tempFilePath);
            }

            $this->crmEquipmentUploadRecordRepository->upsert($upsertData);
            Storage::delete($tempPath . '/' . $zipName);

            DB::connection('mysql_utf8')->commit();
        } catch (\Exception $e) {
            DB::connection('mysql_utf8')->rollBack();
            throw $e;
        }

        return $upsertData;
    }

    /**
     * @param  string  $typeName
     * @param  string  $pathInfo
     * @param  Entry  $entry
     *
     * @return void
     * @throws \Exception
     */
    private function validateRow(string $typeName, string $pathInfo, Entry $entry): void
    {
        if (!in_array($typeName, ['file_BIM', 'file_shop_drawing', 'file_built_drawing']) && in_array($pathInfo, ['.rvt', '.dwg'])) {
            throw new \Exception('附件不符合要求格式,附件檔僅有 BIM 圖資、施工圖以及竣工圖可額外上傳 RVT 和 DWG 格式。');
        }

        if ($typeName === 'images' && in_array($pathInfo, ['.jpg', '.png', '.jpeg']) && ($entry->getSize() / 1024 / 1024 > 5)) {
            throw new \Exception('圖檔不得超過 5M，請檢查圖檔大小再重新匯入。');
        }

        if ($pathInfo === '.pdf' && !in_array($typeName, ComponentFilesType::values())) {
            throw new \Exception('附件不符合要求格式, 附件檔為 PDF。');
        }
    }

    /**
     * @param  UploadedFile  $uploadedFile
     *
     * @return int
     * @throws \Exception
     */
    private function processFileEntry(UploadedFile $uploadedFile): int
    {
        $userID = crm('user_id');

        $fileMagic = FileMagic::parse($uploadedFile)
            ->disk('s3')
            ->path("leasehold/{$userID}")
            ->save();
        return $fileMagic->id;
    }

    /**
     * @param  Entry  $entry
     * @param  string  $fileName
     * @param  string  $pathInfo
     * @param  string  $tempPath
     *
     * @return array
     * @throws \Exception
     */
    private function processUploadedFile(Entry $entry, string $fileName, string $pathInfo, string $tempPath): array
    {
        $hashName = md5($fileName . time()) . '.' . $pathInfo;
        $tempFilePath = $tempPath . '/' . $hashName;

        Storage::put($tempFilePath, $entry->getContent());
        $fullFilePath = Storage::path($tempFilePath);

        if (!file_exists($fullFilePath) || !is_readable($fullFilePath)) {
            throw new \Exception("文件 {$fullFilePath} 不存在或不可讀。");
        }

        $uploadedFile = new UploadedFile(
            $fullFilePath,
            $fileName,
            mime_content_type($fullFilePath),
            null,
            true
        );

        return [$tempFilePath, $uploadedFile];
    }
}
