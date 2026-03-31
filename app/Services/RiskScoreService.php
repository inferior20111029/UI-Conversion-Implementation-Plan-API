<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\InsuranceProfile;

class RiskScoreService
{
    /**
     * 計算並更新寵物的風險分數與保費優惠
     * 計算規則（越高分數 = 越高風險 = 越高保費）
     * 基礎分數：100
     * ---------- 正向加分（高風險）----------
     * - 年齡 > 8 歲：+20
     * - 年齡 > 5 歲：+10
     * - 超過 90 天無健康紀錄：+15
     * - 無疫苗紀錄：+10
     *
     * ---------- 負向扣分（低風險）----------
     * - 健康紀錄數量 >= 5：-10
     * - 最近 30 天有 checkup：-15
     * - 最近 90 天有疫苗接種：-10
     */
    public function calculate(Pet $pet): InsuranceProfile
    {
        $score = 100;
        $discount = 0;

        // 年齡加算
        if ($pet->birthday) {
            $ageYears = now()->diffInYears($pet->birthday);
            if ($ageYears > 8) {
                $score += 20;
            } elseif ($ageYears > 5) {
                $score += 10;
            }
        }

        $records = $pet->healthRecords;

        // 無健康紀錄超過 90 天
        $latest = $records->sortByDesc('recorded_at')->first();
        if (!$latest || now()->diffInDays($latest->recorded_at) > 90) {
            $score += 15;
        }

        // 無疫苗紀錄
        $hasVaccine = $records->where('type', 'vaccine')->isNotEmpty();
        if (!$hasVaccine) {
            $score += 10;
        }

        // 健康紀錄豐富
        if ($records->count() >= 5) {
            $score -= 10;
        }

        // 近 30 天有 checkup
        $recentCheckup = $records
            ->where('type', 'checkup')
            ->filter(fn($r) => now()->diffInDays($r->recorded_at) <= 30)
            ->isNotEmpty();
        if ($recentCheckup) {
            $score -= 15;
        }

        // 近 90 天有疫苗
        $recentVaccine = $records
            ->where('type', 'vaccine')
            ->filter(fn($r) => now()->diffInDays($r->recorded_at) <= 90)
            ->isNotEmpty();
        if ($recentVaccine) {
            $score -= 10;
        }

        // 保費優惠換算（分數越低，優惠越高，最多 20%）
        $score = max(0, min(200, $score)); // clamp between 0-200
        if ($score < 100) {
            $discount = round(((100 - $score) / 100) * 20, 2);
        }

        $profile = $pet->insuranceProfile()->updateOrCreate(
            ['pet_id' => $pet->id],
            [
                'risk_score' => $score,
                'premium_discount' => $discount,
                'last_calculated_at' => now(),
            ]
        );

        return $profile;
    }
}
