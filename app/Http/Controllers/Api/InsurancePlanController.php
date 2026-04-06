<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InsurancePlan;
use App\Models\Pet;
use App\Services\Insurance\PlanPresentationService;
use App\Services\Insurance\PlanRankingService;
use App\Support\Pets\PetInsuranceTypeResolver;
use Illuminate\Http\Request;

class InsurancePlanController extends Controller
{
    public function index(
        Request $request,
        Pet $pet,
        PlanRankingService $planRankingService,
        PlanPresentationService $planPresentationService,
    ) {
        if ($pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $plans = InsurancePlan::query()
            ->with('provider')
            ->where('is_listable', true)
            ->where('source_status', 'active')
            ->get();

        $ranking = $planRankingService->rankForPet($pet, $plans);
        $planMap = $plans->keyBy('id');
        $catalogVersion = InsurancePlan::query()
            ->where('is_listable', true)
            ->where('source_status', 'active')
            ->orderByDesc('source_updated_at')
            ->first()?->source_updated_at?->toISOString();

        return response()->json([
            'success' => true,
            'data' => [
                'plans' => collect($ranking['plans'])
                    ->map(function (array $rankedPlan) use ($planMap, $planPresentationService, $pet): array {
                        /** @var InsurancePlan $plan */
                        $plan = $planMap->get($rankedPlan['plan_id']);

                        return $planPresentationService->listItem($plan, $rankedPlan, $pet->breed);
                    })
                    ->values()
                    ->all(),
                'meta' => [
                    'algorithm_version' => $ranking['algorithm_version'],
                    'catalog_version' => $catalogVersion,
                    'total' => count($ranking['plans']),
                    'pet' => [
                        'id' => $pet->id,
                        'name' => $pet->name,
                        'type' => $pet->type,
                        'type_label' => PetInsuranceTypeResolver::label($pet->type),
                        'breed' => $pet->breed,
                        'microchip_number' => $pet->microchip_number,
                        'has_microchip' => filled($pet->microchip_number),
                        'registration_number' => $pet->registration_number,
                        'is_registered' => (bool) $pet->is_registered,
                        'insurance_type' => PetInsuranceTypeResolver::resolve($pet->type),
                    ],
                ],
            ],
        ]);
    }

    public function show(
        Request $request,
        InsurancePlan $insurancePlan,
        PlanRankingService $planRankingService,
        PlanPresentationService $planPresentationService,
    ) {
        if (! $insurancePlan->is_listable || $insurancePlan->source_status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Insurance plan not found'], 404);
        }

        $insurancePlan->loadMissing('provider');
        $pet = null;

        if ($request->filled('pet_id')) {
            $pet = Pet::query()->findOrFail((int) $request->query('pet_id'));
            if ($pet->user_id !== $request->user()->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        } else {
            $pet = $request->user()->pets()->first();
        }

        $ranking = $pet ? $planRankingService->evaluatePlanForPet($pet, $insurancePlan) : null;

        return response()->json([
            'success' => true,
            'data' => $planPresentationService->detail($insurancePlan, $ranking, $pet?->breed),
        ]);
    }
}
