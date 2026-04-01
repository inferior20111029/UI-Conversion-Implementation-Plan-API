<?php

declare(strict_types=1);

namespace App\Support\Trait\Space;

use Symfony\Component\HttpFoundation\Response;

use App\Repositories\Space\CrmBuildingSpaceRepository;

use App\Support\Enum\FetchMessage;

use App\Support\Response\ApiMessage;

trait CheckTrait
{
    /**
     * 檢查戶別是否存在
     *
     * @param string $spaceId
     *
     * @throws \App\Exceptions\ApiException
     *
     * @return void
     */
    public function spaceExists(string $spaceId): void
    {
        $space = (new CrmBuildingSpaceRepository())->findByUuid($spaceId, crm('company_id'));

        if (empty($space)) {
            (new ApiMessage())->throwException(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
        }
    }
}
