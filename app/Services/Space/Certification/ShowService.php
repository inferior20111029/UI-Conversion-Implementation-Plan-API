<?php

declare(strict_types=1);

namespace App\Services\Space\Certification;

use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

use App\Support\Abstract\Service;
use App\Support\Tool\File\FileMagic;

use App\Models\BuildingSpaceCertificationFile;

use App\Repositories\Certification\BuildingSpaceCertificationRepository;

final class ShowService extends Service
{
    public function __construct(
        private readonly BuildingSpaceCertificationRepository $buildingSpaceCertificationRepository,
    ) {
    }

    /**
     * @param $request
     *
     * @return \Illuminate\Support\Collection
     */
    public function execute($request): Collection
    {
        $spaceId = $request->space_id;
        $type    = $request->type;

        return $this->buildingSpaceCertificationRepository
            ->find($spaceId, $type)
            ->map(fn ($item) => $this->transformCertification($item));
    }

    /**
     * @param $certification
     *
     * @return array
     */
    private function transformCertification($certification): array
    {
        return [
            "id"             => $certification['id'],
            "name"           => $certification['name'],
            "type"           => $certification['type'],
            "application_at" => Carbon::parse($certification['application_at'])->format('Y-m-d'),
            "enable_state"   => $certification['enable_state'],
            "created_at"     => Carbon::parse($certification['created_at'])->format('Y-m-d'),
            'avatar'         => $this->getAvatarUrls($certification->buildingSpaceCertificationFile),
        ];
    }

    /**
     * @param $certificationData
     *
     * @return Collection
     */
    private function getAvatarUrls(Collection $certificationData): Collection
    {
        return $certificationData
            ->reject(fn (BuildingSpaceCertificationFile $certification): bool => empty($certification->file))
            ->map(function (BuildingSpaceCertificationFile $certification): array {
                return [
                   'uuid' => $certification->file?->uuid,
                   'url'  => FileMagic::find($certification->file)->url(),
                ];
            });
    }
}