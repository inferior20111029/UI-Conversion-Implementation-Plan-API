<?php

namespace App\Services\AiDoctor;

use App\Models\Pet;

class AiDoctorPromptBuilder
{
    public function systemPrompt(): string
    {
        return implode("\n", [
            'You are Pet Health OS AI Doctor, a veterinary triage assistant for pet owners in Taiwan.',
            'Always reply in Traditional Chinese.',
            '',
            'Primary goals:',
            '- Provide first-pass triage guidance for pet symptoms.',
            '- Surface safety risks early and recommend appropriate urgency.',
            '- Ask targeted follow-up questions when the information is incomplete.',
            '',
            'Safety boundaries:',
            '- You are not the treating veterinarian and must not claim a confirmed diagnosis.',
            '- Do not prescribe human medication doses or tell the user to ignore urgent warning signs.',
            '- If there are emergency indicators, set severity to high and recommend same-day or immediate veterinary care.',
            '',
            'Reasoning checklist:',
            '- Classify the main symptom category as digestive, skin, respiratory, mobility, urinary, or general.',
            '- Use only low, medium, or high for severity.',
            '- Base the assessment on the symptom description, pet profile, derived symptom signals, and recent health records.',
            '- Prefer concise, practical, safety-oriented guidance over speculative explanations.',
            '',
            'Cost guidance:',
            '- estimated_cost.currency must be TWD.',
            '- estimated_cost.min and estimated_cost.max should be realistic Taiwan outpatient or urgent-care ranges.',
            '- estimated_cost.insurance_savings should be about 20 percent of the midpoint.',
            '',
            'Output requirements:',
            '- Return content that fits the provided JSON schema exactly.',
            '- Keep next_steps, warning_signs, and follow_up_questions concrete and non-repetitive.',
            '- matched_signals should only contain the most relevant symptom signals that influenced the assessment.',
        ]);
    }

    public function contextPrompt(Pet $pet, array $signals): string
    {
        $records = $pet->healthRecords
            ->sortByDesc('recorded_at')
            ->take(4)
            ->map(function ($record): string {
                return sprintf(
                    '- %s | %s | %s',
                    strtoupper((string) $record->type),
                    optional($record->recorded_at)->format('Y-m-d H:i') ?? 'unknown date',
                    $this->stringifyRecordValue($record->value)
                );
            })
            ->implode("\n");

        $signalList = $signals === [] ? 'none' : implode(', ', $signals);

        return implode("\n", [
            'Pet profile:',
            sprintf('- name: %s', $pet->name),
            sprintf('- type: %s', $pet->type),
            sprintf('- breed: %s', $pet->breed ?: 'unknown'),
            sprintf('- weight_kg: %s', $pet->weight ?? 'unknown'),
            sprintf('- age_years: %s', $pet->birthday?->floatDiffInYears(now()) ?? 'unknown'),
            sprintf('- derived symptom signals: %s', $signalList),
            'Recent health records:',
            $records !== '' ? $records : '- none',
        ]);
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
}
