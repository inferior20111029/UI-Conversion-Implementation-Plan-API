<?php

declare(strict_types=1);

namespace App\Services\Space\WarrantySelect;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;

use App\Support\Enum\FetchMessage;

use App\Repositories\Warranty\CrmWarrantySelectRepository;

final class ShowService extends Service
{
    public function __construct(
        private readonly CrmWarrantySelectRepository $crmWarrantySelectRepository,
    ) {
    }

    /**
     * @return Collection
     */
    public function execute(): Collection
    {
        $warrantySelect = $this->crmWarrantySelectRepository
            ->findAll(crm('community_id'))
            ->map(function ($warrantySelect) {
                return[
                    'id'    => $warrantySelect->id,
                    'value' => $warrantySelect->value,
                ];
            });

        if($warrantySelect->isEmpty()) {
            $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
        }

        return $warrantySelect;
    }
}