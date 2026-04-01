<?php

declare(strict_types=1);

namespace App\Support\Trait\RepairRequest;

use App\Support\Tool\File\FileMagic;
use App\Models\RepairRecord;

trait ColumnTrait
{
    /**
     * @param  string  $type
     *
     * @return array
     */
    public function fetchColumnData(string $type = 'created'): array
    {
        $data = request()->only([
            'space',
            'equipment_id',
            'description',
            'space_id',
        ]);

        $data = [
            ...$data,
            ...[
                'comid'                 => crm('community_id'),
                'maintain_category_ids' => implode(",", request()->maintain_id),
                'maintain_info'         => implode(",", request()->maintain),
                $type === 'edit' ? 'updated_at' : 'created_at' => now(),
            ]
        ];

        unset($data['maintain']);

        if ($type === 'edit') {
            unset($data['space_id']);
        }

        return $data;
    }

    /**
     * @param  int|string  $id
     *
     * @return array
     */
    public function fetchFileData(int|string $id): array
    {
        $results = [];

        $video  = request()->post('video');
        $images = request()->post('image');

        if (!empty($images)) {
            foreach ($images as $image) {
                $results[] = [
                    'avatar' => $image,
                    'type'   => 1,
                ];
            }
        }

        if (!is_null($video)) {
            $results[] = [
                'avatar' => $video,
                'type'   => 0,
            ];
        }

        return array_map(fn ($fileData) => [
            'repair_id'  => $id,
            'type'       => $fileData['type'],
            'avatar'     => (int) (FileMagic::find($fileData['avatar'])->get()?->id ?? 0),
            'created_at' => now(),
        ], $results);
    }

    /**
     * @param  RepairRecord  $repairRecord
     *
     * @return array
     */
    public function fetchEditData(RepairRecord $repairRecord): array
    {
        return [
            'space'          => $repairRecord->space,
            'equipment_id'   => $repairRecord->equipment_id,
            'equipment_name' => $repairRecord->equipment->name,
            'description'    => $repairRecord->description,
            'maintain'       => $this->convertStringToArray($repairRecord->maintain_info),
            'maintain_id'    => array_map('intval', explode(',', $repairRecord->maintain_category_ids)),
            'file'           => $repairRecord->repairRecordFile->map(function ($item) {
                $avatar = $item->avatarFile;
                return [
                    'type'      => $item->type ?? '',
                    'file_uuid' => $avatar->uuid ?? '',
                    'url'       => optional(FileMagic::find($avatar))->url(),
                ];
            })->values()->toArray(),
        ];
    }

    private function convertStringToArray($data): array
    {
        if (is_string($data)) {
            return explode(',', $data);
        }

        return $data;
    }

    /**
     * 戶別維修紀錄
     *
     * @param  RepairRecord  $repairRecord
     *
     * @return array
     */
    public function fetchSpaceColumnData(RepairRecord $repairRecord): array
    {
        return [
            'id'             => $repairRecord->id,
            'space'          => $repairRecord->space,
            'equipment_id'   => $repairRecord->equipment_id ?? '',
            'equipment_name' => $repairRecord->equipment->name ?? '',
            'description'    => $repairRecord->description,
            'maintain'       => $repairRecord->maintain_category_ids,
            'status'         => self::repairType()[$repairRecord?->rscPost->f_status ?? 0],
        ];
    }

    public function repairType()
    {
        return [
            0 => '未提報',
            1 => '已提報(尚未修繕)',
            4 => '修繕中',
            5 => '待驗收',
            6 => '修繕完成',
            99 => '未結案',
        ];
    }
}
