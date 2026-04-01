<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAiHealthScanRequest;
use App\Models\AiHealthScan;
use App\Services\AiHealthScanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class AiHealthScanController extends Controller
{
    public function __construct(
        private readonly AiHealthScanService $aiHealthScanService
    ) {}

    public function store(StoreAiHealthScanRequest $request): JsonResponse
    {
        $pet = $request->user()->pets()->find($request->integer('pet_id'));

        if (! $pet) {
            return response()->json([
                'success' => false,
                'message' => '寵物資料不存在',
            ], 404);
        }

        $scan = $this->aiHealthScanService->create($pet, $request->file('file'));

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $scan->id,
                'scan_id' => $scan->id,
                'status' => $scan->status,
            ],
            'message' => '掃描任務已建立',
        ], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $scan = AiHealthScan::query()
            ->whereKey($id)
            ->whereHas('pet', fn ($query) => $query->where('user_id', $request->user()->id))
            ->first();

        if (! $scan) {
            return response()->json([
                'success' => false,
                'message' => '掃描資料不存在',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatScanData($scan),
        ]);
    }

    private function formatScanData(AiHealthScan $scan): array
    {
        if ($scan->status !== AiHealthScan::STATUS_COMPLETED) {
            return [
                'id' => $scan->id,
                'scan_id' => $scan->id,
                'status' => $scan->status,
            ];
        }

        $issues = $this->formatIssues($scan->issues ?? []);

        return [
            'id' => $scan->id,
            'scan_id' => $scan->id,
            'status' => $scan->status,
            'confidence' => $scan->confidence,
            'confidence_score' => $scan->confidence,
            'score' => $scan->score,
            'health_score' => $scan->score,
            'healthScore' => $scan->score,
            'estimated_saving' => $scan->estimated_saving,
            'estimated_savings' => $scan->estimated_saving,
            'preventive_savings' => $scan->estimated_saving,
            'image_url' => url(Storage::url($scan->image_path)),
            'issues' => $issues,
            'findings' => $issues,
        ];
    }

    private function formatIssues(array $issues): array
    {
        return array_map(function (array $issue): array {
            $title = (string) Arr::get($issue, 'title', '');
            $description = (string) Arr::get($issue, 'description', '');
            $advice = (string) Arr::get($issue, 'advice', Arr::get($issue, 'recommendation', ''));
            $saving = (int) Arr::get(
                $issue,
                'saving',
                Arr::get(
                    $issue,
                    'estimated_saving',
                    Arr::get(
                        $issue,
                        'estimated_savings',
                        Arr::get($issue, 'preventive_savings', Arr::get($issue, 'cost_impact', 0))
                    )
                )
            );
            $severity = (string) Arr::get($issue, 'severity', Arr::get($issue, 'level', 'medium'));
            $confidence = Arr::get($issue, 'confidence', Arr::get($issue, 'confidence_score'));

            return [
                'title' => $title,
                'description' => $description,
                'advice' => $advice,
                'recommendation' => $advice,
                'saving' => $saving,
                'estimated_saving' => $saving,
                'estimated_savings' => $saving,
                'preventive_savings' => $saving,
                'cost_impact' => $saving,
                'severity' => $severity,
                'confidence' => $confidence !== null ? (int) $confidence : null,
            ];
        }, $issues);
    }
}
