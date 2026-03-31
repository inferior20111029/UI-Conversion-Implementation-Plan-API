<?php

declare(strict_types=1);

namespace App\Services\RealEstateAgent\Frontend;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;

use App\Models\Login;
use App\Models\Property;
use App\Models\RealEstateAgent;
use App\Models\RealEstateAgentEntrust;

use App\Repositories\RealEstateAgent\RealEstateAgentRepository;

final class ShowPropertyService extends Service
{
    /**
     * @param RealEstateAgentRepository $realEstateAgentRepository
     */
    public function __construct(
        private readonly RealEstateAgentRepository $realEstateAgentRepository
    ) {}

    /**
     * 取得房仲擁有的物件資料
     * @return \Illuminate\Support\Collection
     */
    public function execute(): Collection
    {
        /** @var Login */
        $user = auth()->user();

        $propertyData = $this->fetchData($user->getRealEstateAgentId());
        return $this->fetchResponse($propertyData);
    }

    /**
     * 取得物件資料
     * @param int $realEstateAgentId
     * @return \Illuminate\Support\Collection
     */
    public function fetchData(int $realEstateAgentId): Collection
    {
        $propertyData = $this->realEstateAgentRepository->fetchProperty($realEstateAgentId);

        if ($propertyData->isNotEmpty()) {
            return $propertyData;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得回傳資料
     * @param \Illuminate\Support\Collection $propertyData
     * @return \Illuminate\Support\Collection
     */
    private function fetchResponse(Collection $propertyData): Collection
    {
        return $propertyData
            ->map(function (RealEstateAgent $realEstateAgent): Collection {
                $entrustData = $realEstateAgent->entrust;

                return $entrustData
                    ->reject(fn(RealEstateAgentEntrust $entrust): bool => empty($entrust?->space?->property))
                    ->map(function (RealEstateAgentEntrust $entrust): Collection {
                        $space = $entrust->space;

                        $spaceData = Arr::mapWithKeys(
                            $space->getAttributes(),
                            fn(?string $value, ?string $key): array => [str($key)->camel()->value => $value]
                        );

                        $property = $space->property;

                        return $property
                            ->map(fn(Property $item): array => $item->only('uuid', 'title', 'creator') + $spaceData);
                    });
            })
            ->flatten(2);
    }
}
