# Pet Health OS Consumer API

Laravel BFF for the consumer-facing `pet-health-os` application.

## Responsibilities

這個 repo 目前同時負責：

- consumer auth、pets、dashboard、health records、AI health scan
- 保費列表與方案詳情 API
- 從 provider backend 批次同步 insurance catalog projection
- 在本地重建 provider ranking logic，對 pet 做 eligibility filter 與排序

保費列表的主資料源已經改為 provider insurance plans，不再以 `affiliates` 當保險主列表來源。

## Local Setup

```bash
composer install
touch database/database.sqlite
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve --host=127.0.0.1 --port=8010
```

本機最穩定的開發方式是使用 SQLite。

## Provider Catalog Sync

consumer app 不會在使用者 request-time 直打 provider backend，而是先同步為本地 projection。

需要設定：

```env
PROVIDER_CATALOG_BASE_URL=http://127.0.0.1:8011/api/internal/v1
PROVIDER_CATALOG_SYNC_TOKEN=sync-secret
PROVIDER_CATALOG_SYNC_TIMEOUT=20
```

同步指令：

```bash
php artisan insurance-catalog:sync
php artisan insurance-catalog:sync --full
```

目前排程策略：

- 每小時 incremental sync
- 每日一次 full sync 做 reconciliation

## Insurance APIs

主要保費 endpoints：

- `GET /api/pets/{pet}/insurance/plans`
- `GET /api/insurance/plans/{insurancePlan}`

List API 會：

- 只回 `is_listable=true` 且 `source_status=active` 的方案
- 根據 pet profile 做 eligibility filter
- 在本地計算 ranking
- 回傳 `final_score`、`ranking_position`、`score_breakdown`、`badges`、`why_recommended`

Detail API 會回：

- provider identity
- pricing
- eligibility
- coverage summary
- waiting period
- exclusions
- claim requirements
- score breakdown
- terms url

## Seeded Demo Data

執行 `php artisan migrate:fresh --seed` 後可用：

- demo user：`testUser@email.com` / `password123`
- demo pets：`Bella`、`Milo`

當 provider backend 也完成 seed 並跑過 `insurance-catalog:sync --full` 後，consumer 端會同步出 marketplace catalog。

目前 demo catalog 來源包含：

- `Aurora PetCare Insurance`
- `Summit Pet Mutual`
- `Harbor Companion Insurance`

對 `Bella` 目前可見的 marketplace 方案包含：

- `Aurora Precision Care`
- `Summit Total Care`
- `Harbor Active Paws Plus`
- `Summit Accident Shield`
- `Aurora Everyday Flex`

## Recommended Local Flow

1. 啟動 provider backend：`pet-health-os-api`
2. 啟動 consumer API：這個 repo
3. 執行 `php artisan insurance-catalog:sync --full`
4. 啟動前端 repo：`UI-Conversion-Implementation-Plan`
5. 用 demo user 登入並打開 `/insurance`

## Testing

```bash
php artisan test
```

和這次保費串接直接相關的測試：

- `tests/Feature/Console/InsuranceCatalogSyncCommandTest.php`
- `tests/Feature/Insurance/InsurancePlanApiTest.php`
- `tests/Feature/Insurance/InsuranceRankingParityTest.php`
