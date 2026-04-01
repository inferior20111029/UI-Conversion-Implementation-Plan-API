<?php

declare(strict_types=1);

namespace App\Services\HiddenColumn;

use App\Models\HiddenColumn;
use Illuminate\Support\Collection;

use App\Support\Abstract\Service;

use App\Repositories\HiddenColumn\HiddenColumnRepository;

final class ShowService extends Service
{
    /**
     * @param  HiddenColumnRepository  $hiddenColumnRepository
     */
    public function __construct(
        private readonly HiddenColumnRepository $hiddenColumnRepository,
    ) {
    }

    /**
     * @return Collection
     */
    public function execute(): Collection
    {
        return $this->hiddenColumnRepository
            ->findByUserId(crm('user_id'), crm('company_id'), request()->key)
            ->map(fn (HiddenColumn $hiddenColumn) => json_decode($hiddenColumn->value, true));
    }
}