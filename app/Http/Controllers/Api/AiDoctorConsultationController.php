<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAiDoctorConsultationRequest;
use App\Services\AiDoctorConsultationService;
use Illuminate\Http\JsonResponse;

class AiDoctorConsultationController extends Controller
{
    public function __construct(
        private readonly AiDoctorConsultationService $aiDoctorConsultationService,
    ) {}

    public function store(StoreAiDoctorConsultationRequest $request): JsonResponse
    {
        $pet = $request->user()->pets()
            ->with(['healthRecords' => fn ($query) => $query->latest('recorded_at')->take(6)])
            ->find($request->integer('pet_id'));

        if (! $pet) {
            return response()->json([
                'success' => false,
                'message' => '找不到要諮詢的寵物。',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->aiDoctorConsultationService->consult(
                $pet,
                $request->string('message')->toString(),
                $request->input('history', []),
            ),
        ]);
    }
}
