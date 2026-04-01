<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use Illuminate\Support\Collection;

use App\Models\ContractDocument;
use App\Support\Tool\File\FileMagic;

final class UpdateDocument
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * 更新合約文件
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $document = $updateInstance->contractData->document;
        $documentRequest = array_unique((array) $updateInstance->request->post('document'));

        if ($document->isNotEmpty() && empty($documentRequest)) {
            $this->deleteFile($updateInstance, $document);
            return;
        }

        if (empty($documentRequest)) {
            return;
        }

        $file = FileMagic::find($documentRequest)->get();

        if (empty($file)) {
            return;
        }

        [$create, $update, $document] = $this->fetchHandleData($updateInstance, $document, $file);

        $updateInstance->contractData->document()->upsert([...$create, ...$update], ['id']);

        if ($document->isNotEmpty()) {
            $this->deleteFile($updateInstance, $document);
        }
    }

    /**
     * 取得處理資料
     *
     * @param \App\Services\RenterContract\Component\UpdateInstance $updateInstance 更新實例
     * @param \Illuminate\Support\Collection $document 當前擁有的文件資料
     * @param array $file 檔案資料
     *
     * @return array
     */
    private function fetchHandleData(UpdateInstance $updateInstance, Collection $document, array $file): array
    {
        $contractRelationKey = $this->fetchContractRelationKey(ContractDocument::class);
        $contractColumn = $this->contractColumn($updateInstance->contractData, $contractRelationKey);

        $create = [];
        $update = [];

        foreach ($file as $value) {
            $fileId = (int) data_get($value, 'id');

            $target = $document->where($contractRelationKey, $updateInstance->contractData->id)->where('file_id', $fileId);
            $id = $target->value('id');

            $columnData = $this->fetchDocumentColumnData($fileId)
                ->replace([
                    ...compact('id'), ...$contractColumn, ...$this->uuidColumn($target->value('uuid')), ...['file_id' => $fileId]
                ])
                ->toColumnArray();

            if ($target->isNotEmpty()) {
                $update[] = $columnData;
                $document->forget($target->keys()->first());

                continue;
            }

            $create[] = $columnData;
        }

        return [$create, $update, $document];
    }

    /**
     * 刪除檔案
     *
     * @param \App\Services\RenterContract\Component\UpdateInstance $updateInstance 更新實例
     * @param \Illuminate\Support\Collection $document 當前擁有的文件資料
     *
     * @return void
     */
    private function deleteFile(UpdateInstance $updateInstance, Collection $document): void
    {
        $delete = $document->pluck('id')->all();
        $updateInstance->contractData->document()->whereIn('id', $delete)->forceDelete();

        FileMagic::find($document->pluck('file_id')->all())->delete();
    }
}
