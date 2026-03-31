<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

use Illuminate\Support\Collection;

use App\Models\CrmBuildingSpaceDocument;

use App\Support\Tool\File\FileMagic;

final class UpdateDocument
{
    use \App\Support\Trait\PropertyRights\ColumnTrait;

    /**
     * 更新戶別文件
     *
     * @param UpdateInstance $updateInstance 更新實例
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $document = $updateInstance->spaceData->document;
        $documentRequest = array_unique((array) $updateInstance->request->post('document'));

        if ($document->isNotEmpty() && empty($documentRequest)) {
            $this->deleteFile($updateInstance, $document);
            return;
        }

        $file = FileMagic::find($documentRequest)->get();

        if (empty($file)) {
            return;
        }

        [$create, $update, $document] = $this->fetchHandleData($updateInstance, $document, $file);
        $updateInstance->spaceData->document()->upsert([...$create, ...$update], ['id']);

        if ($document->isNotEmpty()) {
            $this->deleteFile($updateInstance, $document);
        }
    }

    /**
     * 取得處理資料
     *
     * @param \App\Services\PropertyRights\Component\UpdateInstance $updateInstance 更新實例
     * @param \Illuminate\Support\Collection $document 當前擁有的文件資料
     * @param array $file 文件資料
     *
     * @return array
     */
    private function fetchHandleData(UpdateInstance $updateInstance, Collection $document, array $file): array
    {
        $spaceId = $updateInstance->fetchSpaceId();
        $spaceRelationKey = $this->fetchSpaceRelationKey(CrmBuildingSpaceDocument::class);
        $spaceColumn = $this->contractColumn($updateInstance->spaceData, $spaceRelationKey);

        $create = [];
        $update = [];

        foreach ($file as $value) {
            $fileId = (int) data_get($value, 'id');

            $target = $document->where($spaceRelationKey, $spaceId)->where('file_id', $fileId);
            $id = $target->value('id');

            $column = $this->fetchDocumentColumnData($fileId)
                ->replace([
                    ...compact('id'), ...$spaceColumn, ...['file_id' => $fileId]
                ])
                ->toColumnArray();

            if ($target->isNotEmpty()) {
                $update[] = $column;
                $document->forget($target->keys()->first());

                continue;
            }

            $create[] = $column;
        }

        return [$create, $update, $document];
    }

    /**
     * 刪除檔案
     *
     * @param \App\Services\PropertyRights\Component\UpdateInstance $updateInstance 更新實例
     * @param \Illuminate\Support\Collection $document 欲刪除的文件資料
     *
     * @return void
     */
    private function deleteFile(UpdateInstance $updateInstance, Collection $document): void
    {
        $delete = $document->pluck('id')->all();
        $updateInstance->spaceData->document()->whereIn('id', $delete)->forceDelete();

        FileMagic::find($document->pluck('file_id')->all())->delete();
    }
}
