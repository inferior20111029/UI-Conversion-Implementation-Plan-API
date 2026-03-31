<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthRecord;
use App\Models\Pet;
use App\Http\Requests\StoreHealthRecordRequest;
use App\Services\RiskScoreService;
use Illuminate\Http\Request;

class HealthRecordController extends Controller
{
    public function index(Pet $pet, Request $request)
    {
        if ($pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $pet->healthRecords()->latest('recorded_at')->get()
        ]);
    }

    public function store(StoreHealthRecordRequest $request, Pet $pet)
    {
        if ($pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $record = $pet->healthRecords()->create([
            'type' => $request->type,
            'value' => $request->value,
            'recorded_at' => $request->recorded_at ?? now(),
        ]);

        // Auto-recalculate risk score after new health record
        $pet->load('healthRecords');
        $profile = app(RiskScoreService::class)->calculate($pet);

        return response()->json([
            'success' => true,
            'message' => 'Health record created successfully',
            'data' => [
                'record' => $record,
                'insurance_profile' => $profile,
            ]
        ], 201);
    }

    public function show(HealthRecord $record, Request $request)
    {
        if ($record->pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $record
        ]);
    }

    public function destroy(HealthRecord $record, Request $request)
    {
        if ($record->pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Health record deleted successfully'
        ]);
    }
}
