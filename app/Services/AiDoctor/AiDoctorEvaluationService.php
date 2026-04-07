<?php

namespace App\Services\AiDoctor;

use App\Models\HealthRecord;
use App\Models\Pet;
use App\Services\AiDoctorConsultationService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use RuntimeException;

class AiDoctorEvaluationService
{
    public function __construct(
        private readonly AiDoctorConsultationService $consultationService,
    ) {}

    public function runSuite(string $suite, string $providerMode = 'config'): array
    {
        $suiteDefinition = $this->loadSuite($suite);
        $cases = $suiteDefinition['cases'] ?? [];
        $originalProvider = config('services.ai_doctor.provider');

        if ($providerMode !== 'config') {
            config(['services.ai_doctor.provider' => $providerMode]);
        }

        try {
            $results = collect($cases)
                ->map(fn (array $case): array => $this->evaluateCase($case))
                ->values();
        } finally {
            config(['services.ai_doctor.provider' => $originalProvider]);
        }

        $passed = $results->where('passed', true)->count();
        $total = $results->count();

        return [
            'suite' => $suiteDefinition['name'] ?? $suite,
            'provider' => $providerMode === 'config'
                ? (string) config('services.ai_doctor.provider')
                : $providerMode,
            'summary' => [
                'passed' => $passed,
                'failed' => $total - $passed,
                'total' => $total,
            ],
            'results' => $results->all(),
        ];
    }

    private function evaluateCase(array $case): array
    {
        $pet = $this->buildPet($case['pet'] ?? [], (string) ($case['id'] ?? 'eval-case'));
        $response = $this->consultationService->consult(
            $pet,
            (string) ($case['message'] ?? ''),
            $case['history'] ?? [],
        );

        $expected = $case['expected'] ?? [];
        $actual = [
            'category' => Arr::get($response, 'assessment.category'),
            'severity' => Arr::get($response, 'assessment.severity'),
            'provider' => Arr::get($response, 'meta.provider'),
        ];

        $mismatches = [];

        foreach (['category', 'severity', 'provider'] as $field) {
            if (array_key_exists($field, $expected) && $expected[$field] !== $actual[$field]) {
                $mismatches[] = "{$field}: expected {$expected[$field]}, got {$actual[$field]}";
            }
        }

        foreach (($expected['reply_contains'] ?? []) as $needle) {
            if (! str_contains((string) Arr::get($response, 'reply', ''), (string) $needle)) {
                $mismatches[] = "reply missing '{$needle}'";
            }
        }

        return [
            'id' => (string) ($case['id'] ?? 'eval-case'),
            'description' => (string) ($case['description'] ?? ''),
            'expected' => $expected,
            'actual' => $actual,
            'passed' => $mismatches === [],
            'mismatches' => $mismatches,
        ];
    }

    private function loadSuite(string $suite): array
    {
        $path = resource_path("ai-doctor/evals/{$suite}.php");

        if (! is_file($path)) {
            throw new RuntimeException("AI doctor eval suite not found: {$suite}");
        }

        $definition = require $path;

        if (! is_array($definition) || ! isset($definition['cases']) || ! is_array($definition['cases'])) {
            throw new RuntimeException("AI doctor eval suite is invalid: {$suite}");
        }

        return $definition;
    }

    private function buildPet(array $definition, string $fallbackId): Pet
    {
        $pet = new Pet();
        $pet->forceFill([
            'id' => abs(crc32($fallbackId)),
            'name' => $definition['name'] ?? 'Eval Pet',
            'type' => $definition['type'] ?? 'dog',
            'breed' => $definition['breed'] ?? 'mixed',
            'weight' => $definition['weight'] ?? 10.0,
            'birthday' => $definition['birthday'] ?? now()->subYears(4)->toDateString(),
        ]);

        $records = collect($definition['health_records'] ?? [])
            ->map(function (array $record) use ($pet): HealthRecord {
                $healthRecord = new HealthRecord();
                $healthRecord->forceFill([
                    'pet_id' => $pet->id,
                    'type' => $record['type'] ?? 'checkup',
                    'value' => $record['value'] ?? ['note' => 'No recent note'],
                    'recorded_at' => isset($record['recorded_at'])
                        ? Carbon::parse($record['recorded_at'])
                        : now(),
                ]);

                return $healthRecord;
            });

        $pet->setRelation('healthRecords', $records instanceof Collection ? $records : collect($records));

        return $pet;
    }
}
