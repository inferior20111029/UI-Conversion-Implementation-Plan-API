<?php

declare(strict_types=1);

namespace App\Services\Selected;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;

use App\Models\Property;

use App\Repositories\PropertyManage\PropertyRepository;

final class PropertyService extends Service
{
    /**
     * 取得租售資料
     * @return \Illuminate\Support\Collection
     */
    public function execute(): Collection
    {
        $propertyData = $this->fetchData();
        return $this->fetchResponse($propertyData);
    }

    /**
     * 取得物件資料
     * @throws \App\Exceptions\ApiException
     * @return \Illuminate\Support\Collection
     */
    private function fetchData(): Collection
    {
        $propertyData = (new PropertyRepository())->findAll(crm('company_id'), crm('community_id'));

        if ($propertyData->isNotEmpty()) {
            return $propertyData;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得回傳資料
     *
     * @param \Illuminate\Support\Collection $propertyData
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchResponse(Collection $propertyData): Collection
    {
        return $propertyData
            ->map(function (Property $property): array {
                $space = $property->crmBuildingSpace;

                return  collect($space)
                    ->mapWithKeys(fn($value, string $key): array => [str($key)->camel()->value => (string) $value])
                    ->merge([
                        'uuid'  => $property->uuid,
                        'title' => $property->title,
                    ])
                    ->toArray();
            });
    }
}
