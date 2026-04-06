<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Support\Pets\PetInsuranceTypeResolver;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $pets = $request->user()->pets()->with('insuranceProfile')->get();

        return response()->json([
            'success' => true,
            'data' => $pets->map(fn (Pet $pet): array => $this->petPayload($pet))->values()->all(),
        ]);
    }

    public function store(StorePetRequest $request)
    {
        $pet = $request->user()->pets()->create($request->validated());

        // Initialize Insurance Profile for MVP
        $pet->insuranceProfile()->create([
            'risk_score' => 100,
            'premium_discount' => 0.00,
            'last_calculated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pet created successfully',
            'data' => $this->petPayload($pet->load('insuranceProfile'))
        ], 201);
    }

    public function show(Pet $pet, Request $request)
    {
        if ($pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $this->petPayload($pet->load(['healthRecords' => function ($query) {
                $query->latest('recorded_at')->take(5);
            }, 'insuranceProfile']))
        ]);
    }

    public function update(UpdatePetRequest $request, Pet $pet)
    {
        if ($pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $pet->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pet updated successfully',
            'data' => $this->petPayload($pet->load('insuranceProfile'))
        ]);
    }

    public function destroy(Pet $pet, Request $request)
    {
        if ($pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $pet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pet deleted successfully'
        ]);
    }

    private function petPayload(Pet $pet): array
    {
        $payload = $pet->toArray();
        $payload['type_label'] = PetInsuranceTypeResolver::label($pet->type);
        $payload['insurance_type'] = PetInsuranceTypeResolver::resolve($pet->type);
        $payload['has_microchip'] = filled($pet->microchip_number);

        return $payload;
    }
}
