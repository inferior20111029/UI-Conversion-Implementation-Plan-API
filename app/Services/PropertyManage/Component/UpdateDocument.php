<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Component;

use App\Models\PropertyDocument;
use Illuminate\Support\Collection;

use App\Models\File;
use App\Support\Tool\File\FileMagic;

final class UpdateDocument
{
    use \App\Support\Trait\PropertyManage\ColumnTrait;

    /**
     * 更新物件文件
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $documents = $updateInstance->propertyData->document()->get();
        $documentRequest = $updateInstance->request->post('document');

        $pictureFiles = $this->processDocumentType($updateInstance, $documents, $documentRequest, 'picture');
        $videoFile    = $this->processDocumentType($updateInstance, $documents, $documentRequest, 'video');

        $this->processURLType($updateInstance, $documents, $documentRequest, 'URL');


        $documentsToSave = [
            ...$this->createDocuments($pictureFiles, 'picture'),
            ...$this->createDocuments($videoFile, 'video'),
        ];

        $updateInstance->propertyData->document()->saveMany($documentsToSave);
    }

    /**
     * 處理文件類型
     *
     * @param  UpdateInstance  $updateInstance
     * @param  Collection  $documents
     * @param  array|null  $documentRequest
     * @param  string  $type
     *
     * @return File|array
     */
    private function processDocumentType(UpdateInstance $updateInstance, Collection $documents, ?array $documentRequest, string $type): File|array
    {
        $ids = array_map(fn($item) => $item['uuid'],$documentRequest[$type] ?? []);

        if ($documents->where('type', $type)->isNotEmpty() && empty($ids)) {
            $this->deleteFile($updateInstance, $documents, $type);
            return [];
        }

        $this->deleteDocumentFile($updateInstance, $documents, $type);

        $files = FileMagic::find($ids)->get();
        return  $files ?? [];
    }

    /**
     * 處理URL類型
     *
     * @param  UpdateInstance  $updateInstance
     * @param  Collection  $documents
     * @param  array|null  $documentRequest
     * @param  string  $type
     *
     * @return void
     */
    private function processURLType(UpdateInstance $updateInstance, Collection $documents, ?array $documentRequest, string $type): void
    {
        $url = data_get($documentRequest, $type);

        if (is_null($url)) {
            if ($documents->where('type', $type)->isNotEmpty()) {
                $updateInstance->propertyData->document()->where('type', $type)->forceDelete();
            }
            return;
        }

        if ($documents->where('type', $type)->isNotEmpty()) {
            $updateInstance->propertyData->document()->where('type', $type)->update([
                'url' => $url
            ]);
        } else {
            $updateInstance->propertyData->document()->create([
                'property_id' => $updateInstance->propertyData->id,
                'file_id'     => 0,
                'url'         => $url,
                'type'        => $type
            ]);
        }
    }

    /**
     * 刪除檔案
     *
     * @param  UpdateInstance  $updateInstance
     * @param  Collection  $documents
     * @param  string  $type
     *
     * @return void
     */
    private function deleteFile(UpdateInstance $updateInstance, Collection $documents, string $type): void
    {
        $deleteIds = $documents->where('type', $type)->pluck('file_id')->all();

        $updateInstance->propertyData->document()->whereIn('file_id', $deleteIds)->forceDelete();

        FileMagic::find($deleteIds)->delete();
    }

    /**
     * 刪除檔案
     *
     * @param  UpdateInstance  $updateInstance
     * @param  Collection  $documents
     * @param  string  $type
     *
     * @return void
     */
    private function deleteDocumentFile(UpdateInstance $updateInstance, Collection $documents, string $type): void
    {
        $deleteIds = $documents->where('type', $type)->pluck('file_id')->all();

        $updateInstance->propertyData->document()->whereIn('file_id', $deleteIds)->forceDelete();
    }

    /**
     * 文件
     *
     * @param  array  $files
     * @param  string  $type
     *
     * @return array
     */
    private function createDocuments(array $files, string $type): array
    {
        $relationKey = $this->fetchPropertyRelationKey(PropertyDocument::class);

        return array_filter(array_map(function ($file) use ($relationKey, $type) {
            if ($file) {
                $fileId = (int) data_get($file, 'id', 0);
                return new PropertyDocument(
                    $this->fetchDocumentColumnData($fileId, $type, $file)
                        ->excludeColumn($relationKey, 'url')
                        ->toColumnArray()
                );
            }
            return null;
        }, $files));
    }
}
