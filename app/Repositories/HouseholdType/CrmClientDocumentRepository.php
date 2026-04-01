<?php

declare(strict_types=1);

namespace App\Repositories\HouseholdType;

use App\Models\CrmClientDocument;

class CrmClientDocumentRepository
{
    /**
     * @param $data
     *
     * @return bool
     */
    public function insert($data): bool
    {
        return CrmClientDocument::insert($data);
    }

    /**
     * @param  string  $clientCompanyId
     * @param  array  $fileId
     *
     * @return int
     */
    public function forceDelete(string $clientCompanyId, array $fileId): int
    {
        return CrmClientDocument::where('client_company_id', $clientCompanyId)
            ->whereIn('file_id', $fileId)
            ->forceDelete();
    }
}
