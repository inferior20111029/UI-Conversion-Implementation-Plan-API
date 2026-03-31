<?php

declare(strict_types=1);

namespace App\Services\Space\Certification;

use Illuminate\Support\Arr;

use App\Support\Abstract\Service;
use App\Support\Tool\File\FileMagic;

use App\Repositories\Certification\BuildingSpaceCertificationRepository;
use App\Repositories\Certification\BuildingSpaceCertificationFileRepository;

final class UpdateService extends Service
{
    use \App\Support\Trait\Certification\ColumnTrait;
    public function __construct(
        private readonly BuildingSpaceCertificationRepository $buildingSpaceCertificationRepository,
        private readonly BuildingSpaceCertificationFileRepository $buildingSpaceCertificationFileRepository,
    ) {
    }

    /**
     * 取的編輯資訊
     *
     * @param $request
     * @param  int  $id
     *
     * @return void
     */
    public function execute($request, int $id)
    {
        $this->buildingSpaceCertificationRepository->upsert(self::fetchPatchColumnData($request, $id));

        $fileIds = $request->file_id ?? [];

        if (!empty($fileIds)) {
            $files = collect($fileIds)->map(function ($fileId) use ($id) {
                return [
                    'certification_id' => $id,
                    'avatar'           => (int) FileMagic::find($fileId)->get()?->id,
                ];
            });

            $this->buildingSpaceCertificationFileRepository->forceDelete($files->pluck('certification_id')->toArray());
            $this->buildingSpaceCertificationFileRepository->insert($files->toArray());
        }
    }

    /**
     * @param $request
     *
     * @return void
     */
    public function batch($request)
    {
        $spaceIds = $request->post('space_id');
        $dataList = $request->post('data');

        $now    = now();
        $insert = array_reduce($spaceIds, function ($carry, $spaceId) use ($dataList, $now) {
            return [
                ...$carry, ...array_map(fn ($data) => [
                    ...Arr::except($data, ['file_id']),
                    ...[
                        'space_id' => $spaceId,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                ], $dataList),
            ];
        }, []);

        $fileArray = array_reduce(
            array_map(fn ($data) => [
                $data['type'] => FileMagic::find($data['file_id'])->get()[0]['id'] ?? 0
            ], $dataList),
            fn ($carry, $item) => $carry + $item,
            []
        );

        $this->buildingSpaceCertificationRepository->inserts($insert);

        $buildingSpaceCertification = $this->buildingSpaceCertificationRepository
            ->findId($spaceIds, $now)
            ->pluck('type', 'id');

        $files = $buildingSpaceCertification->map(fn ($type, $id) => [
            'certification_id' => $id,
            'avatar' => $fileArray[$type] ?? 0,
        ]);

        $this->buildingSpaceCertificationFileRepository->insert($files->toArray());
    }
}
