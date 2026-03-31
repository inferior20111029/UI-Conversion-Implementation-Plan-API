<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Pet;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Pet $pet, Request $request)
    {
        if ($pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $pet->activities()->latest('occurred_at')->get()
        ]);
    }

    public function store(Request $request, Pet $pet)
    {
        if ($pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'type' => 'required|string',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer',
            'occurred_at' => 'nullable|date',
        ]);

        $activity = $pet->activities()->create([
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'occurred_at' => $validated['occurred_at'] ?? now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Activity recorded successfully',
            'data' => $activity
        ], 201);
    }

    public function show(Activity $activity, Request $request)
    {
        if ($activity->pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $activity
        ]);
    }

    public function destroy(Activity $activity, Request $request)
    {
        if ($activity->pet->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $activity->delete();

        return response()->json([
            'success' => true,
            'message' => 'Activity deleted successfully'
        ]);
    }
}
