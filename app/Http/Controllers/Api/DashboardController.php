<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Aggregated data for a specific pet's dashboard.
     * Matches the frontend [Dashboard.tsx] expected structure.
     */
    public function petDashboard(Pet $pet, Request $request)
    {
        if ($pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $profile = $pet->insuranceProfile;
        
        // Formulate Risk Profile
        $score = $profile ? $profile->risk_score : 100;
        $riskLevel = 'LOW';
        if ($score > 110) $riskLevel = 'HIGH';
        else if ($score > 100) $riskLevel = 'MEDIUM';

        // Fake some factors and recommendations for UI demo based on score
        $factors = [];
        $recommendations = [];
        if ($score >= 100) {
            $factors[] = '缺少近期疫苗紀錄';
            $recommendations[] = '預約施打年度疫苗可獲得 10% 保費折扣';
        }
        if ($pet->birthday && \Carbon\Carbon::parse($pet->birthday)->age > 5) {
            $factors[] = '進入高齡犬貓期';
            $recommendations[] = '建議增加每年健檢頻率';
        }

        // Weight Trend
        $weightTrend = $pet->healthRecords()
            ->where('type', 'weight')
            ->oldest('recorded_at')
            ->get()
            ->map(function($record) {
                return [
                    'date' => $record->recorded_at->toDateString(),
                    'weight_kg' => (float) str_replace('kg', '', $record->value),
                ];
            });

        // Recent Records
        $recentRecords = $pet->healthRecords()
            ->latest('recorded_at')
            ->limit(5)
            ->get()
            ->map(function($record) {
                return [
                    'id' => $record->id,
                    'record_type' => strtoupper($record->type),
                    'date' => $record->recorded_at->toDateString(),
                    'value' => $record->value,
                    'notes' => '由系統自動產生',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pet->id,
                'name' => $pet->name,
                'type' => $pet->type,
                'risk_profile' => [
                    'score' => $score,
                    'risk_level' => $riskLevel,
                    'factors' => $factors,
                    'recommendations' => $recommendations,
                ],
                'discount_status' => [
                    'discount_percent' => $profile ? $profile->premium_discount : 0,
                ],
                'weight_trend' => $weightTrend,
                'recent_health_records' => $recentRecords,
            ]
        ]);
    }
}
