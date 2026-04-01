<?php

namespace App\Http\Controllers;

use App\Services\AiHealthAnalyzerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiHealthController extends Controller
{
    public function analyze(Request $request, AiHealthAnalyzerService $service): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'file', 'image', 'max:5120'],
        ]);

        $result = $service->analyze($request->file('image'));

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
