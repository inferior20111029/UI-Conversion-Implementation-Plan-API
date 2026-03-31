<?php

declare(strict_types=1);

namespace App\Repositories\HouseholdType;

use App\Models\CrmClient;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class CrmClientRepository
{
    use \App\Support\Trait\Paginate\PaginateTrait;

    /**
     * @param array $data
     * @return int
     */
    public function insertGetId($data): int
    {
        return CrmClient::insertGetId($data);
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function insert($data): bool
    {
        return CrmClient::insert($data);
    }

    /**
     * @param $data
     *
     * @return CrmClient|null
     */
    public function create($data)
    {
        return CrmClient::create($data);
    }

    /**
     * @param $data
     *
     * @return CrmClient|null
     */
    public function updateOrCreate($data)
    {
        return CrmClient::updateOrCreate([
            'identity_number' => $data['identity_number'],
            'company_id'      => $data['company_id'],
        ], Arr::except($data, ['company_id', 'identity_number']));
    }

    /**
     * @param $data
     *
     * @return CrmClient|null
     */
    public function updateOrCreateId($data)
    {
        return CrmClient::updateOrCreate([
            'id' => $data['id'],
        ], Arr::except($data, ['company_id']));
    }

    /**
     * @param $data
     *
     * @return CrmClient|null
     */
    public function upsert($data): int
    {
        return CrmClient::upsert($data, ['id']);
    }

    /**
     * @return Collection
     */
    public function find(array $ids): Collection
    {
        return CrmClient::where('company_id', crm('company_id'))
            ->whereIn('id', $ids)
            ->with([
                'crmClientContact',
                'crmClientHasCompany.crmClientDocument.file',
            ])
            ->get();
    }


    /**
     * 尋找身分證資料
     *
     * @param  string  $identity
     *
     * @return CrmClient|null
     */
    public function findIdentityNumber(string $identity): ?CrmClient
    {
        return CrmClient::where('company_id', crm('company_id'))
            ->where('identity_number', $identity)
            ->first();
    }

    /**
     * 客戶總覽
     *
     * @param  int  $companyId
     * @param  array  $data
     *
     * @return LengthAwarePaginator
     */
    public function paginate(int $companyId, array $data): LengthAwarePaginator
    {
        return CrmClient::where('company_id', $companyId)
            ->when($data['name'] ?? null, fn (Builder | BelongsTo $query, $name) => $query->where('name', 'LIKE', "%{$name}%"))
            ->whereHas('crmPropertyTransactionInfo.crmPropertyInfoList', fn (Builder | BelongsTo $query) => $query->where('is_edit', 1))
            ->whereHas('crmPropertyTransactionInfo.crmBuildingSpace', fn (Builder | BelongsTo  $query) => $query->where('company_id', $companyId)->whereNull('deleted_at'))
            ->whereHas('crmClientRelatedPerson.crmClient', fn (Builder | BelongsTo  $query) => $query->where('company_id', $companyId))
            ->whereHas('crmClientContact', function (Builder | HasMany $query) use ($data) {
                $query->where(function (Builder $q) use ($data) {
                    if (isset($data['phone'])) {
                        $q->where(function (Builder $q) use ($data) {
                            $q->where('type', 'phone')
                                ->where('value', 'LIKE', "%{$data['phone']}%");
                        });
                    }
                    if (isset($data['email'])) {
                        $q->orWhere(function (Builder $q) use ($data) {
                            $q->where('type', 'email')
                                ->where('value', 'LIKE', "%{$data['email']}%");
                        });
                    }
                });
            })
            ->with([
                'crmPropertyTransactionInfo',
                'crmPropertyTransactionInfo.crmPropertyInfoList',
                'crmClientRelatedPerson.crmClient' => fn (Builder | BelongsTo $query) => $query->where('company_id', $companyId),
                'crmPropertyTransactionInfo.crmBuildingSpace' => fn (Builder | BelongsTo $query) => $query->where('company_id', $companyId)->whereNull('deleted_at'),
                'crmClientContact',
                'crmClientHasCompany'
            ])
            ->paginate($this->paginateLimit());
    }

    /**
     * @param  array  $data
     *
     * @return array
     */
    public function updateOrCreateBatch(array $data): array
    {
        $ids = [];

        foreach ($data as $attributes) {
            $model = CrmClient::updateOrCreate(
                ['identity_number' => $attributes['identity_number']],
                $attributes
            );
            $ids[$attributes['identity_number']] = $model->id;
        }

        return $ids;
    }
}