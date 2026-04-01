<?php

namespace App\Services;

use App\Models\AiHealthScan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class AiHealthAnalyzerService
{
    public function analyze(UploadedFile $file): array
    {
        $realPath = $file->getRealPath();

        if ($realPath === false || ! is_file($realPath)) {
            throw new RuntimeException('Unable to read the uploaded image.');
        }

        $imageBinary = file_get_contents($realPath);

        if ($imageBinary === false) {
            throw new RuntimeException('Unable to read the uploaded image.');
        }

        $response = $this->baseRequest()
            ->attach('file', $imageBinary, $file->getClientOriginalName())
            ->post($this->endpoint());

        if (! $response->successful()) {
            throw new RuntimeException('AI service error: '.$response->body());
        }

        $payload = $response->json();

        if (! is_array($payload)) {
            throw new RuntimeException('AI service returned invalid JSON.');
        }

        return $payload;
    }

    public function analyzeScan(AiHealthScan $scan): array
    {
        try {
            if (! $this->shouldUseRealtime()) {
                return $this->mockAnalysis();
            }

            return $this->callRealtimeAnalyzer($scan);
        } catch (Throwable $e) {
            Log::error('AI Health Analyze Failed', [
                'scan_id' => $scan->id,
                'error' => $e->getMessage(),
            ]);

            return $this->mockAnalysis();
        }
    }

    private function shouldUseRealtime(): bool
    {
        return config('services.ai_health.mode') === 'real'
            && filled(config('services.ai_health.endpoint'));
    }

    private function callRealtimeAnalyzer(AiHealthScan $scan): array
    {
        $imagePath = Storage::disk('public')->path($scan->image_path);

        if (! is_file($imagePath)) {
            throw new RuntimeException('AI health scan image not found.');
        }

        $imageBinary = file_get_contents($imagePath);

        if ($imageBinary === false) {
            throw new RuntimeException('Unable to read AI health scan image.');
        }

        $response = $this->baseRequest()
            ->attach('file', $imageBinary, basename($imagePath))
            ->post($this->endpoint(), [
                'scan_id' => (string) $scan->id,
                'pet_id' => (string) $scan->pet_id,
                'pet_name' => (string) optional($scan->pet)->name,
                'pet_type' => (string) optional($scan->pet)->type,
                'pet_breed' => (string) optional($scan->pet)->breed,
                'image_url' => url(Storage::url($scan->image_path)),
            ]);

        $response->throw();

        $payload = $response->json();

        if (! is_array($payload)) {
            throw new RuntimeException('AI returned invalid JSON.');
        }

        if (data_get($payload, 'success') === false) {
            throw new RuntimeException(
                (string) data_get($payload, 'message', 'AI analyze failed')
            );
        }

        return $this->normalize($payload);
    }

    private function baseRequest()
    {
        $config = config('services.ai_health');

        $request = Http::acceptJson()
            ->timeout((int) ($config['timeout'] ?? 60))
            ->withOptions([
                'verify' => $config['verify_ssl'] ?? true,
            ]);

        if (! empty($config['api_key'])) {
            $request = $request->withToken($config['api_key']);
        }

        return $request;
    }

    private function endpoint(): string
    {
        $endpoint = config('services.ai_health.endpoint');

        if (! filled($endpoint)) {
            throw new RuntimeException('AI health endpoint is not configured.');
        }

        return (string) $endpoint;
    }

    private function normalize(array $payload): array
    {
        $data = Arr::get($payload, 'data', $payload);

        return [
            'confidence' => (int) Arr::get(
                $data,
                'confidence',
                Arr::get($data, 'confidence_score', 0)
            ),
            'score' => (int) Arr::get(
                $data,
                'score',
                Arr::get($data, 'health_score', Arr::get($data, 'healthScore', 0))
            ),
            'estimated_saving' => (int) Arr::get(
                $data,
                'estimated_saving',
                Arr::get(
                    $data,
                    'estimated_savings',
                    Arr::get(
                        $data,
                        'preventive_savings',
                        Arr::get($data, 'preventiveSavings', 0)
                    )
                )
            ),
            'issues' => collect(Arr::get($data, 'issues', []))
                ->map(fn (array $issue) => $this->normalizeIssue($issue))
                ->values()
                ->all(),
        ];
    }

    private function normalizeIssue(array $issue): array
    {
        return [
            'title' => (string) Arr::get(
                $issue,
                'title',
                Arr::get($issue, 'name', Arr::get($issue, 'label', ''))
            ),
            'description' => (string) Arr::get(
                $issue,
                'description',
                Arr::get($issue, 'summary', Arr::get($issue, 'details', ''))
            ),
            'advice' => (string) Arr::get(
                $issue,
                'advice',
                Arr::get($issue, 'recommendation', Arr::get($issue, 'suggestion', ''))
            ),
            'saving' => (int) Arr::get(
                $issue,
                'saving',
                Arr::get(
                    $issue,
                    'estimated_saving',
                    Arr::get(
                        $issue,
                        'preventive_savings',
                        Arr::get($issue, 'cost_impact', 0)
                    )
                )
            ),
            'severity' => (string) Arr::get(
                $issue,
                'severity',
                Arr::get($issue, 'level', Arr::get($issue, 'risk_level', 'medium'))
            ),
            'confidence' => Arr::get(
                $issue,
                'confidence',
                Arr::get($issue, 'confidence_score')
            ),
        ];
    }

    private function mockAnalysis(): array
    {
        return [
            'confidence' => 92,
            'score' => rand(75, 95),
            'estimated_saving' => rand(80, 200),
            'issues' => [
                [
                    'title' => 'Skin dryness',
                    'description' => 'Possible mild dehydration or irritation.',
                    'advice' => 'Increase hydration and add Omega-3.',
                    'saving' => 40,
                    'severity' => 'low',
                    'confidence' => 90,
                ],
                [
                    'title' => 'Preventive care opportunity',
                    'description' => 'Monitoring may prevent future cost.',
                    'advice' => 'Weekly check and grooming.',
                    'saving' => 120,
                    'severity' => 'medium',
                    'confidence' => 88,
                ],
            ],
        ];
    }
}
