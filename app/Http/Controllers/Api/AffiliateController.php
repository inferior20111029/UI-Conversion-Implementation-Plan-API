<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\Pet;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    /**
     * GET /api/affiliates
     * 列出所有啟用中的保險合作夥伴
     */
    public function index()
    {
        $affiliates = Affiliate::where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'data' => $affiliates,
        ]);
    }

    /**
     * GET /api/pets/{pet}/affiliates
     * 根據寵物的風險分數，篩選出最適合的保險推薦清單
     */
    public function recommend(Request $request, Pet $pet)
    {
        if ($pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $profile = $pet->insuranceProfile;

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Insurance profile not found. Please add health records first.',
            ], 404);
        }

        $riskScore = $profile->risk_score;
        $petType = $pet->type;

        $recommendations = Affiliate::where('is_active', true)
            ->where('min_risk_score', '<=', $riskScore)
            ->where('max_risk_score', '>=', $riskScore)
            ->get()
            ->filter(function ($affiliate) use ($petType) {
                // 若無限制寵物類型，則全部適用
                if (empty($affiliate->target_pet_types)) {
                    return true;
                }
                return in_array($petType, $affiliate->target_pet_types);
            })
            ->values();

        $score = $profile->risk_score;
        $riskLevel = 'LOW';
        if ($score > 110) $riskLevel = 'HIGH';
        else if ($score > 100) $riskLevel = 'MEDIUM';

        // Unlock actions for the UI
        $unlockActions = [];
        if ($score >= 100) {
            $unlockActions[] = '新增一筆近期疫苗紀錄';
            $unlockActions[] = '更新本月體重紀錄';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pet->id, // Frontend uses discount.id sometimes
                'name' => $pet->name,
                'risk_level' => $riskLevel,
                'discount_percent' => $profile->premium_discount,
                'unlock_actions' => $unlockActions,
                'pet' => [
                    'id' => $pet->id,
                    'name' => $pet->name,
                    'type' => $pet->type,
                ],
                'insurance_profile' => [
                    'risk_score' => $profile->risk_score,
                    'premium_discount' => $profile->premium_discount,
                    'last_calculated_at' => $profile->last_calculated_at?->toISOString(),
                ],
                'recommendations' => $recommendations,
            ],
        ]);
    }

    /**
     * POST /api/affiliates/{affiliate}/click
     * 記錄點擊回饋並導向目標網址
     */
    public function logClick(Request $request, Affiliate $affiliate)
    {
        // MVP: Just log to console or log file for now
        \Log::info("User {$request->user()->id} clicked affiliate: {$affiliate->name}");

        return response()->json([
            'success' => true,
            'data' => [
                'redirect_url' => $affiliate->affiliate_url,
            ],
        ]);
    }
}
