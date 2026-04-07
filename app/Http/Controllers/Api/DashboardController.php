<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthRecord;
use App\Models\InsurancePlan;
use App\Models\Pet;
use App\Services\Insurance\PlanPresentationService;
use App\Services\Insurance\PlanRankingService;
use App\Support\Pets\PetInsuranceTypeResolver;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function petDashboard(
        Pet $pet,
        Request $request,
        PlanRankingService $planRankingService,
        PlanPresentationService $planPresentationService,
    ) {
        if ($pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $pet->loadMissing('insuranceProfile');

        $records = $pet->healthRecords()
            ->latest('recorded_at')
            ->get();

        $activities = $pet->activities()
            ->latest('occurred_at')
            ->get();

        $weightTrendRecords = $pet->healthRecords()
            ->where('type', 'weight')
            ->orderBy('recorded_at')
            ->get();

        $weightRecords = $records
            ->where('type', 'weight')
            ->values();

        $latestWeightRecord = $weightRecords->first();
        $previousWeightRecord = $weightRecords->skip(1)->first();
        $lastCheckupRecord = $records->firstWhere('type', 'checkup');
        $lastVaccineRecord = $records->firstWhere('type', 'vaccine');

        $profile = $pet->insuranceProfile;
        $score = (int) ($profile?->risk_score ?? 100);
        $discountPercent = round((float) ($profile?->premium_discount ?? 0), 2);

        $healthSummary = $this->buildHealthSummary(
            $pet,
            $latestWeightRecord,
            $lastCheckupRecord,
            $lastVaccineRecord,
        );

        $activitySummary = $this->buildActivitySummary($activities);
        $recommendedPlan = $this->buildRecommendedPlan(
            $pet,
            $discountPercent,
            $healthSummary,
            $planRankingService,
            $planPresentationService,
        );

        $riskInsights = $this->buildRiskInsights(
            $healthSummary,
            $activitySummary,
            $latestWeightRecord,
            $lastCheckupRecord,
            $lastVaccineRecord,
            $discountPercent,
        );

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pet->id,
                'name' => $pet->name,
                'type' => $pet->type,
                'type_label' => PetInsuranceTypeResolver::label($pet->type),
                'insurance_type' => PetInsuranceTypeResolver::resolve($pet->type),
                'breed' => $pet->breed,
                'microchip_number' => $pet->microchip_number,
                'has_microchip' => filled($pet->microchip_number),
                'registration_number' => $pet->registration_number,
                'is_registered' => (bool) $pet->is_registered,
                'risk_profile' => [
                    'score' => $score,
                    'risk_level' => $this->resolveRiskLevel($score),
                    'factors' => collect($riskInsights)->pluck('title')->values()->all(),
                    'recommendations' => collect($riskInsights)->pluck('recommendation')->values()->all(),
                    'insights' => $riskInsights,
                    'updated_at' => $profile?->last_calculated_at?->toISOString(),
                ],
                'discount_status' => [
                    'discount_percent' => $discountPercent,
                    'market_price_monthly' => $recommendedPlan['market_price_monthly'],
                    'discounted_price_monthly' => $recommendedPlan['discounted_price_monthly'],
                    'monthly_savings' => $recommendedPlan['monthly_savings'],
                    'next_milestone' => $recommendedPlan['next_milestone'],
                ],
                'recommended_plan' => $recommendedPlan,
                'health_summary' => $healthSummary,
                'latest_weight' => $this->buildLatestWeightPayload(
                    $latestWeightRecord,
                    $previousWeightRecord,
                    $pet,
                ),
                'activity_summary' => $activitySummary,
                'preventive_care' => [
                    'checkup' => $this->buildPreventiveCarePayload(
                        'checkup',
                        '定期健檢',
                        $lastCheckupRecord,
                        180,
                        '建議至少每 6 個月安排一次健檢。',
                    ),
                    'vaccine' => $this->buildPreventiveCarePayload(
                        'vaccine',
                        '疫苗保護',
                        $lastVaccineRecord,
                        365,
                        '建議確認核心疫苗是否仍在保護期內。',
                    ),
                ],
                'weight_trend' => $weightTrendRecords
                    ->map(fn (HealthRecord $record): array => [
                        'date' => $record->recorded_at?->toDateString() ?? $record->created_at?->toDateString(),
                        'weight_kg' => $this->extractWeightKg($record),
                    ])
                    ->filter(fn (array $point): bool => $point['date'] !== null && $point['weight_kg'] !== null)
                    ->values()
                    ->all(),
                'recent_health_records' => $records
                    ->take(5)
                    ->map(fn (HealthRecord $record): array => $this->mapRecentRecord($record))
                    ->values()
                    ->all(),
            ],
        ]);
    }

    private function buildHealthSummary(
        Pet $pet,
        ?HealthRecord $latestWeightRecord,
        ?HealthRecord $lastCheckupRecord,
        ?HealthRecord $lastVaccineRecord,
    ): array {
        $items = collect([
            $this->healthChecklistItem(
                'breed',
                '品種資料',
                filled($pet->breed),
                '補齊品種後，可更準確比對承保規則與推薦條件。',
            ),
            $this->healthChecklistItem(
                'microchip',
                '晶片資訊',
                filled($pet->microchip_number),
                '部分保單要求晶片資料，建議先補齊。',
            ),
            $this->healthChecklistItem(
                'registration',
                '寵物登記',
                (bool) $pet->is_registered,
                '完成寵登後，可符合更多方案資格。',
            ),
            $this->healthChecklistItem(
                'weight',
                '近期體重',
                $this->isRecordRecent($latestWeightRecord, 60),
                '最近 60 天更新一次體重，可讓風險評估更準。',
            ),
            $this->healthChecklistItem(
                'checkup',
                '近期健檢',
                $this->isRecordRecent($lastCheckupRecord, 180),
                '建議至少每 6 個月維持一次健檢紀錄。',
            ),
            $this->healthChecklistItem(
                'vaccine',
                '疫苗紀錄',
                $this->isRecordRecent($lastVaccineRecord, 365),
                '維持疫苗紀錄可提升風險評估完整度。',
            ),
        ]);

        $completedCount = $items->where('completed', true)->count();
        $totalCount = max($items->count(), 1);

        return [
            'completion_percent' => (int) round(($completedCount / $totalCount) * 100),
            'completed_items_count' => $completedCount,
            'total_items_count' => $totalCount,
            'items' => $items->values()->all(),
            'missing_items' => $items->where('completed', false)->values()->all(),
        ];
    }

    private function buildActivitySummary(Collection $activities): array
    {
        $now = now();
        $lastSevenDaysActivities = $activities->filter(
            fn ($activity): bool => $activity->occurred_at !== null
                && $activity->occurred_at->greaterThanOrEqualTo($now->copy()->subDays(7))
        );

        $todayMinutes = (int) $activities
            ->filter(fn ($activity): bool => $activity->occurred_at !== null && $activity->occurred_at->isToday())
            ->sum('duration_minutes');

        $minutesLast7Days = (int) $lastSevenDaysActivities->sum('duration_minutes');
        $sessionCount = $lastSevenDaysActivities->count();
        $latestActivity = $activities->first();

        return [
            'today_minutes' => $todayMinutes,
            'minutes_last_7_days' => $minutesLast7Days,
            'sessions_last_7_days' => $sessionCount,
            'last_activity_at' => $latestActivity?->occurred_at?->toISOString(),
            'last_activity_label' => $latestActivity ? $this->activityTypeLabel((string) $latestActivity->type) : null,
            'progress' => (int) min(100, round(($minutesLast7Days / 140) * 100)),
            'note' => $sessionCount > 0
                ? "最近 7 天共有 {$sessionCount} 筆活動紀錄"
                : '尚無活動紀錄，建議先從散步或互動玩耍開始。',
            'has_device_data' => false,
        ];
    }

    private function buildRecommendedPlan(
        Pet $pet,
        float $discountPercent,
        array $healthSummary,
        PlanRankingService $planRankingService,
        PlanPresentationService $planPresentationService,
    ): array {
        $activePlans = InsurancePlan::query()
            ->with('provider')
            ->where('is_listable', true)
            ->where('source_status', 'active')
            ->get();

        $basePlan = [
            'id' => null,
            'provider_name' => null,
            'name' => $pet->name.' 專屬守護方案',
            'currency' => 'TWD',
            'market_price_monthly' => 50.0,
            'discounted_price_monthly' => round(50 * (1 - ($discountPercent / 100)), 2),
            'monthly_savings' => round(50 * ($discountPercent / 100), 2),
            'ranking_position' => null,
            'final_score' => null,
            'badges' => [],
            'why_recommended' => ['依目前健康資料估算保費與風險等級。'],
        ];

        if ($activePlans->isEmpty()) {
            return $this->appendNextMilestone($basePlan, $discountPercent, $healthSummary);
        }

        $ranking = $planRankingService->rankForPet($pet, $activePlans);
        $topRanked = collect($ranking['plans'])->first();

        if ($topRanked === null) {
            return $this->appendNextMilestone($basePlan, $discountPercent, $healthSummary);
        }

        $plan = $activePlans->firstWhere('id', $topRanked['plan_id']);
        if ($plan === null) {
            return $this->appendNextMilestone($basePlan, $discountPercent, $healthSummary);
        }

        $presented = $planPresentationService->listItem($plan, $topRanked, $pet->breed);
        $marketPriceMonthly = round((($plan->annual_premium_min + $plan->annual_premium_max) / 2) / 12, 2);
        $discountedPriceMonthly = round($marketPriceMonthly * (1 - ($discountPercent / 100)), 2);

        return $this->appendNextMilestone([
            'id' => $plan->id,
            'provider_name' => $presented['provider_name'],
            'name' => $presented['name'],
            'currency' => $plan->currency ?: 'TWD',
            'market_price_monthly' => $marketPriceMonthly,
            'discounted_price_monthly' => $discountedPriceMonthly,
            'monthly_savings' => round(max($marketPriceMonthly - $discountedPriceMonthly, 0), 2),
            'ranking_position' => $presented['ranking_position'],
            'final_score' => $presented['final_score'],
            'badges' => $presented['badges'],
            'why_recommended' => $presented['why_recommended'],
        ], $discountPercent, $healthSummary);
    }

    private function appendNextMilestone(array $plan, float $discountPercent, array $healthSummary): array
    {
        $missingItems = collect($healthSummary['missing_items'] ?? []);
        $nextDiscountPercent = $missingItems->isEmpty()
            ? $discountPercent
            : min(20, round($discountPercent + 2, 2));

        $plan['next_milestone'] = [
            'title' => $missingItems->isEmpty()
                ? '維持目前健康紀錄，可保留已解鎖折扣'
                : '完成 '.$missingItems->first()['label'].' 可再解鎖折扣',
            'progress_percent' => (int) ($healthSummary['completion_percent'] ?? 0),
            'projected_price_monthly' => round(
                $plan['market_price_monthly'] * (1 - ($nextDiscountPercent / 100)),
                2
            ),
            'target_discount_percent' => $nextDiscountPercent,
            'helper_text' => $missingItems->isEmpty()
                ? '目前健康資料完整度良好，持續維持即可。'
                : '優先補齊：'.$missingItems
                    ->take(2)
                    ->pluck('label')
                    ->implode('、'),
        ];

        return $plan;
    }

    private function buildRiskInsights(
        array $healthSummary,
        array $activitySummary,
        ?HealthRecord $latestWeightRecord,
        ?HealthRecord $lastCheckupRecord,
        ?HealthRecord $lastVaccineRecord,
        float $discountPercent,
    ): array {
        $insights = collect();
        $completionPercent = (int) ($healthSummary['completion_percent'] ?? 0);
        $weightAgeDays = $this->recordAgeInDays($latestWeightRecord);
        $checkupAgeDays = $this->recordAgeInDays($lastCheckupRecord);
        $vaccineAgeDays = $this->recordAgeInDays($lastVaccineRecord);

        if ($lastVaccineRecord === null) {
            $insights->push($this->riskInsight(
                'vaccine_missing',
                '尚未建立疫苗紀錄',
                '目前無法確認核心疫苗保護狀態。',
                'warning',
                '補登最近一次疫苗名稱與日期，可提升健康檔案完整度。',
                'increase',
                3,
                '%',
                '折扣影響',
            ));
        } elseif ($vaccineAgeDays !== null && $vaccineAgeDays > 365) {
            $insights->push($this->riskInsight(
                'vaccine_due',
                '疫苗保護需要追蹤',
                "最近一次疫苗紀錄已是 {$vaccineAgeDays} 天前。",
                'warning',
                '建議確認是否需要補強或更新疫苗資訊。',
                'increase',
                2,
                '%',
                '風險偏移',
            ));
        }

        if ($lastCheckupRecord === null) {
            $insights->push($this->riskInsight(
                'checkup_missing',
                '缺少近期健檢紀錄',
                '目前沒有可用的健檢資料可供風險判讀。',
                'warning',
                '安排一次健檢並回填摘要，可讓平台提供更準確建議。',
                'increase',
                2,
                '%',
                '風險偏移',
            ));
        } elseif ($checkupAgeDays !== null && $checkupAgeDays > 180) {
            $insights->push($this->riskInsight(
                'checkup_stale',
                '健檢資料超過建議週期',
                "最近一次健檢是 {$checkupAgeDays} 天前。",
                'warning',
                '建議補做年度健檢，並更新主要結論與醫囑。',
                'increase',
                2,
                '%',
                '風險偏移',
            ));
        }

        if ($latestWeightRecord === null) {
            $insights->push($this->riskInsight(
                'weight_missing',
                '缺少體重追蹤',
                '尚未建立足夠的體重紀錄。',
                'warning',
                '先新增一筆最新體重，有助於觀察健康變化。',
                'increase',
                1,
                '%',
                '資料缺口',
            ));
        } elseif ($weightAgeDays !== null && $weightAgeDays > 60) {
            $insights->push($this->riskInsight(
                'weight_stale',
                '體重資料需要更新',
                "最近一次體重紀錄是 {$weightAgeDays} 天前。",
                'warning',
                '更新近期體重後，系統可更準確評估保費折扣空間。',
                'increase',
                1,
                '%',
                '資料缺口',
            ));
        }

        if (($activitySummary['minutes_last_7_days'] ?? 0) > 0 && ($activitySummary['minutes_last_7_days'] ?? 0) < 60) {
            $insights->push($this->riskInsight(
                'activity_low',
                '近期活動量偏低',
                "最近 7 天僅記錄 {$activitySummary['minutes_last_7_days']} 分鐘活動。",
                'warning',
                '增加散步或玩耍頻率，能讓健康趨勢更穩定。',
                'increase',
                1,
                '%',
                '健康趨勢',
            ));
        }

        if ($completionPercent < 100) {
            $insights->push($this->riskInsight(
                'profile_incomplete',
                '健康檔案尚未完整',
                "目前檔案完整度為 {$completionPercent}%。",
                'warning',
                '優先補齊缺少的基本資料與健康紀錄，可提升推薦精準度。',
                'increase',
                2,
                '%',
                '推薦精準度',
            ));
        }

        if ($insights->isEmpty()) {
            $insights->push($this->riskInsight(
                'healthy_baseline',
                '健康狀態良好',
                '目前主要健康訊號穩定，資料完整度也足夠。',
                'success',
                '維持規律更新紀錄，可持續保留目前折扣。',
                'decrease',
                max(1, (int) round($discountPercent ?: 1)),
                '%',
                '已鎖定折扣',
            ));
        }

        return $insights->take(3)->values()->all();
    }

    private function buildLatestWeightPayload(
        ?HealthRecord $latestWeightRecord,
        ?HealthRecord $previousWeightRecord,
        Pet $pet,
    ): array {
        $latestWeightKg = $this->extractWeightKg($latestWeightRecord);
        $previousWeightKg = $this->extractWeightKg($previousWeightRecord);
        $fallbackWeight = $pet->weight !== null ? (float) $pet->weight : null;
        $weightKg = $latestWeightKg ?? $fallbackWeight;
        $weightAgeDays = $this->recordAgeInDays($latestWeightRecord);
        $delta = ($latestWeightKg !== null && $previousWeightKg !== null)
            ? round($latestWeightKg - $previousWeightKg, 1)
            : null;

        return [
            'value_kg' => $weightKg,
            'recorded_at' => $latestWeightRecord?->recorded_at?->toISOString(),
            'delta_kg' => $delta,
            'progress' => $this->recencyProgress($weightAgeDays, 60),
            'note' => match (true) {
                $latestWeightRecord === null && $fallbackWeight !== null => '目前使用寵物基本資料中的體重。',
                $latestWeightRecord === null => '尚未建立體重紀錄。',
                $weightAgeDays !== null && $weightAgeDays <= 30 => '最近 30 天內已更新體重。',
                $weightAgeDays !== null && $weightAgeDays <= 60 => '體重資料仍可用，建議近期再追蹤一次。',
                default => '體重紀錄較久未更新，建議補一筆新資料。',
            },
        ];
    }

    private function buildPreventiveCarePayload(
        string $key,
        string $label,
        ?HealthRecord $record,
        int $freshnessWindowDays,
        string $fallbackNote,
    ): array {
        $daysSince = $this->recordAgeInDays($record);

        return [
            'key' => $key,
            'label' => $label,
            'status' => match (true) {
                $record === null => 'missing',
                $daysSince !== null && $daysSince <= $freshnessWindowDays => 'good',
                default => 'attention',
            },
            'days_since' => $daysSince,
            'last_recorded_at' => $record?->recorded_at?->toISOString(),
            'progress' => $this->recencyProgress($daysSince, $freshnessWindowDays),
            'note' => match (true) {
                $record === null => $fallbackNote,
                $daysSince !== null && $daysSince <= $freshnessWindowDays => "最近更新於 {$daysSince} 天前。",
                default => "距離最近一次紀錄已 {$daysSince} 天，建議重新確認。",
            },
        ];
    }

    private function mapRecentRecord(HealthRecord $record): array
    {
        return [
            'id' => $record->id,
            'record_type' => strtoupper($record->type),
            'record_type_label' => $this->recordTypeLabel($record->type),
            'date' => $record->recorded_at?->toDateString() ?? $record->created_at?->toDateString(),
            'value' => $this->decodeRecordValue($record->value),
            'notes' => $this->recordSummary($record),
            'summary' => $this->recordSummary($record),
        ];
    }

    private function healthChecklistItem(string $key, string $label, bool $completed, string $helper): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'completed' => $completed,
            'helper' => $helper,
        ];
    }

    private function riskInsight(
        string $key,
        string $title,
        string $currentStatus,
        string $statusType,
        string $recommendation,
        string $impactType,
        int $impactAmount,
        string $impactUnit,
        string $impactLabel,
    ): array {
        return [
            'key' => $key,
            'title' => $title,
            'current_status' => $currentStatus,
            'status_type' => $statusType,
            'recommendation' => $recommendation,
            'financial_impact' => [
                'type' => $impactType,
                'amount' => $impactAmount,
                'unit' => $impactUnit,
                'label' => $impactLabel,
            ],
        ];
    }

    private function resolveRiskLevel(int $score): string
    {
        return match (true) {
            $score > 110 => 'high',
            $score > 100 => 'medium',
            default => 'low',
        };
    }

    private function isRecordRecent(?HealthRecord $record, int $windowDays): bool
    {
        $days = $this->recordAgeInDays($record);

        return $days !== null && $days <= $windowDays;
    }

    private function recordAgeInDays(?HealthRecord $record): ?int
    {
        if ($record?->recorded_at === null) {
            return null;
        }

        return (int) $record->recorded_at->startOfDay()->diffInDays(now()->copy()->startOfDay());
    }

    private function recencyProgress(?int $daysSince, int $targetDays): int
    {
        if ($daysSince === null) {
            return 0;
        }

        $remaining = max($targetDays - $daysSince, 0);

        return (int) round(($remaining / max($targetDays, 1)) * 100);
    }

    private function extractWeightKg(?HealthRecord $record): ?float
    {
        if ($record === null) {
            return null;
        }

        $decoded = $this->decodeRecordValue($record->value);

        if (is_numeric($decoded)) {
            return round((float) $decoded, 1);
        }

        if (is_array($decoded) && isset($decoded['weight_kg']) && is_numeric($decoded['weight_kg'])) {
            return round((float) $decoded['weight_kg'], 1);
        }

        if (is_string($decoded) && preg_match('/([0-9]+(?:\.[0-9]+)?)/', $decoded, $matches) === 1) {
            return round((float) $matches[1], 1);
        }

        return null;
    }

    private function decodeRecordValue(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $trimmed = trim($value);
        if ($trimmed === '') {
            return null;
        }

        if (($trimmed[0] === '{' || $trimmed[0] === '[') && $this->isJson($trimmed)) {
            return json_decode($trimmed, true);
        }

        return $trimmed;
    }

    private function isJson(string $value): bool
    {
        json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE;
    }

    private function recordSummary(HealthRecord $record): string
    {
        $decoded = $this->decodeRecordValue($record->value);

        return match ($record->type) {
            'weight' => ($this->extractWeightKg($record) !== null)
                ? '體重 '.$this->extractWeightKg($record).' kg'
                : '已更新體重資料',
            'vaccine' => is_array($decoded) && filled($decoded['name'] ?? null)
                ? '疫苗：'.$decoded['name']
                : (is_string($decoded) && $decoded !== '' ? '疫苗：'.$decoded : '已更新疫苗紀錄'),
            'checkup' => is_array($decoded) && filled($decoded['note'] ?? null)
                ? '健檢：'.$decoded['note']
                : (is_string($decoded) && $decoded !== '' ? '健檢：'.$decoded : '已更新健檢紀錄'),
            default => '已更新健康紀錄',
        };
    }

    private function recordTypeLabel(string $type): string
    {
        return match (strtolower($type)) {
            'weight' => '體重',
            'vaccine' => '疫苗',
            'checkup' => '健檢',
            default => strtoupper($type),
        };
    }

    private function activityTypeLabel(string $type): string
    {
        return match (strtolower($type)) {
            'walk' => '散步',
            'play' => '玩耍',
            'feeding' => '餵食',
            'grooming' => '美容',
            default => $type,
        };
    }
}
