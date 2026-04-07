<?php

namespace App\Services;

use App\Models\Pet;
use App\Services\AiDoctor\AiDoctorPromptBuilder;
use App\Services\AiDoctor\AiDoctorResponseSchema;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JsonException;
use RuntimeException;
use Throwable;

class AiDoctorConsultationService
{
    public function __construct(
        private readonly AiDoctorPromptBuilder $promptBuilder,
        private readonly AiDoctorResponseSchema $responseSchema,
        private readonly RuleBasedAiDoctorConsultationService $fallback,
    ) {}

    public function consult(Pet $pet, string $message, array $history = []): array
    {
        [$sanitizedMessage, $signals] = $this->extractSignals($message);
        $sanitizedHistory = $this->sanitizeHistory($history);

        if ($this->shouldUseOpenAi()) {
            try {
                $response = $this->consultWithOpenAi($pet, $sanitizedMessage, $sanitizedHistory, $signals);
                $response['meta']['provider'] = 'openai';
                $response['meta']['model'] = (string) config('services.ai_doctor.model');

                return $response;
            } catch (Throwable $e) {
                Log::warning('AI doctor OpenAI consultation failed, falling back to rule-based triage.', [
                    'pet_id' => $pet->id,
                    'message' => $sanitizedMessage,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $fallback = $this->fallback->consult($pet, $sanitizedMessage, $sanitizedHistory, $signals);
        $fallback['meta']['provider'] = 'fallback';
        $fallback['meta']['model'] = null;

        return $fallback;
    }

    private function shouldUseOpenAi(): bool
    {
        return config('services.ai_doctor.provider') === 'openai'
            && filled(config('services.ai_doctor.api_key'));
    }

    private function consultWithOpenAi(Pet $pet, string $message, array $history, array $signals): array
    {
        $response = $this->baseRequest()->post($this->endpoint(), [
            'model' => config('services.ai_doctor.model'),
            'store' => false,
            'max_output_tokens' => 1400,
            'input' => $this->buildInput($pet, $message, $history, $signals),
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'pet_consultation',
                    'description' => 'Structured veterinary triage assessment for a pet owner.',
                    'strict' => true,
                    'schema' => $this->responseSchema->definition(),
                ],
            ],
        ]);

        $response->throw();

        $payload = $response->json();
        $text = data_get($payload, 'output.0.content.0.text') ?? data_get($payload, 'output_text');

        if (! is_string($text) || trim($text) === '') {
            throw new RuntimeException('OpenAI response did not contain structured text.');
        }

        try {
            $structured = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException('OpenAI returned invalid JSON: '.$e->getMessage(), previous: $e);
        }

        if (! is_array($structured)) {
            throw new RuntimeException('OpenAI returned an unexpected response payload.');
        }

        return $this->normalizeOpenAiAssessment($pet, $structured, data_get($payload, 'id'));
    }

    private function buildInput(Pet $pet, string $message, array $history, array $signals): array
    {
        $messages = [
            [
                'role' => 'system',
                'content' => [
                    [
                        'type' => 'input_text',
                        'text' => $this->promptBuilder->systemPrompt(),
                    ],
                ],
            ],
            [
                'role' => 'system',
                'content' => [
                    [
                        'type' => 'input_text',
                        'text' => $this->promptBuilder->contextPrompt($pet, $signals),
                    ],
                ],
            ],
        ];

        foreach (array_slice($history, -6) as $item) {
            $role = ($item['role'] ?? 'user') === 'ai' ? 'assistant' : ($item['role'] ?? 'user');
            $content = trim((string) ($item['content'] ?? ''));

            if ($content === '') {
                continue;
            }

            $messages[] = [
                'role' => in_array($role, ['assistant', 'user'], true) ? $role : 'user',
                'content' => [
                    [
                        'type' => 'input_text',
                        'text' => $content,
                    ],
                ],
            ];
        }

        $messages[] = [
            'role' => 'user',
            'content' => [
                [
                    'type' => 'input_text',
                    'text' => $message,
                ],
            ],
        ];

        return $messages;
    }

    private function normalizeOpenAiAssessment(Pet $pet, array $structured, ?string $responseId): array
    {
        $assessment = Arr::get($structured, 'assessment', []);
        $cost = Arr::get($assessment, 'estimated_cost', []);
        $recentRecords = $pet->healthRecords
            ->sortByDesc('recorded_at')
            ->take(3)
            ->map(fn ($record): array => [
                'type' => $record->type,
                'recorded_at' => optional($record->recorded_at)->toISOString(),
                'summary' => $this->stringifyRecordValue($record->value),
            ])
            ->values()
            ->all();

        return [
            'reply' => (string) Arr::get($structured, 'reply', ''),
            'assessment' => [
                'category' => (string) Arr::get($assessment, 'category', 'general'),
                'summary' => (string) Arr::get($assessment, 'summary', 'General health review'),
                'severity' => (string) Arr::get($assessment, 'severity', 'low'),
                'confidence' => (int) Arr::get($assessment, 'confidence', 75),
                'triage' => (string) Arr::get($assessment, 'triage', 'Observe at home and keep monitoring'),
                'recommendation' => (string) Arr::get($assessment, 'recommendation', ''),
                'estimated_cost' => [
                    'currency' => (string) Arr::get($cost, 'currency', 'TWD'),
                    'min' => (int) Arr::get($cost, 'min', 500),
                    'max' => (int) Arr::get($cost, 'max', 2000),
                    'insurance_savings' => (int) Arr::get($cost, 'insurance_savings', 300),
                ],
                'next_steps' => array_values(array_slice(Arr::get($assessment, 'next_steps', []), 0, 4)),
                'warning_signs' => array_values(array_slice(Arr::get($assessment, 'warning_signs', []), 0, 4)),
                'follow_up_questions' => array_values(array_slice(Arr::get($assessment, 'follow_up_questions', []), 0, 4)),
                'matched_signals' => array_values(array_slice(Arr::get($assessment, 'matched_signals', []), 0, 5)),
            ],
            'pet' => [
                'id' => $pet->id,
                'name' => $pet->name,
                'type' => $pet->type,
                'breed' => $pet->breed,
                'weight' => $pet->weight,
                'age_years' => $pet->birthday?->floatDiffInYears(now()),
                'recent_records' => $recentRecords,
            ],
            'meta' => [
                'generated_at' => now()->toISOString(),
                'disclaimer' => 'AI guidance is for triage support only and does not replace a veterinarian diagnosis.',
                'response_id' => $responseId,
            ],
        ];
    }

    private function sanitizeHistory(array $history): array
    {
        return collect($history)
            ->filter(fn ($item): bool => is_array($item))
            ->map(function (array $item): array {
                [$content, $signals] = $this->extractSignals((string) ($item['content'] ?? ''));

                return [
                    'role' => $item['role'] ?? 'user',
                    'content' => $content,
                    'signals' => $signals,
                ];
            })
            ->values()
            ->all();
    }

    private function extractSignals(string $message): array
    {
        preg_match('/\[signals:([a-z0-9_, -]+)\]/i', $message, $matches);

        $signals = collect(explode(',', $matches[1] ?? ''))
            ->map(fn (string $signal): string => trim(strtolower($signal)))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $sanitizedMessage = trim((string) preg_replace('/\s*\[signals:[^\]]+\]\s*/i', '', $message));

        return [$sanitizedMessage, $signals];
    }

    private function stringifyRecordValue(mixed $value): string
    {
        if (is_array($value)) {
            $parts = collect($value)
                ->map(fn (mixed $item, string $key): ?string => is_scalar($item) && $item !== ''
                    ? "{$key}: {$item}"
                    : null)
                ->filter()
                ->values()
                ->all();

            return $parts !== [] ? implode(' / ', $parts) : 'Record updated';
        }

        if (is_string($value)) {
            $trimmed = trim($value);

            if ($trimmed !== '' && (str_starts_with($trimmed, '{') || str_starts_with($trimmed, '['))) {
                $decoded = json_decode($trimmed, true);

                if (is_array($decoded)) {
                    return $this->stringifyRecordValue($decoded);
                }
            }

            if ($trimmed !== '') {
                return $trimmed;
            }
        }

        return 'Record updated';
    }

    private function baseRequest()
    {
        return Http::acceptJson()
            ->timeout((int) config('services.ai_doctor.timeout', 45))
            ->withOptions([
                'verify' => config('services.ai_doctor.verify_ssl', true),
            ])
            ->withToken((string) config('services.ai_doctor.api_key'));
    }

    private function endpoint(): string
    {
        return rtrim((string) config('services.ai_doctor.base_url', 'https://api.openai.com/v1'), '/').'/responses';
    }
}
