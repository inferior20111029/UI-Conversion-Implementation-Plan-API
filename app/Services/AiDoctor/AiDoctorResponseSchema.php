<?php

namespace App\Services\AiDoctor;

class AiDoctorResponseSchema
{
    public function definition(): array
    {
        return [
            'type' => 'object',
            'additionalProperties' => false,
            'properties' => [
                'reply' => ['type' => 'string'],
                'assessment' => [
                    'type' => 'object',
                    'additionalProperties' => false,
                    'properties' => [
                        'category' => [
                            'type' => 'string',
                            'enum' => ['digestive', 'skin', 'respiratory', 'mobility', 'urinary', 'general'],
                        ],
                        'summary' => ['type' => 'string'],
                        'severity' => [
                            'type' => 'string',
                            'enum' => ['low', 'medium', 'high'],
                        ],
                        'confidence' => ['type' => 'integer', 'minimum' => 0, 'maximum' => 100],
                        'triage' => ['type' => 'string'],
                        'recommendation' => ['type' => 'string'],
                        'estimated_cost' => [
                            'type' => 'object',
                            'additionalProperties' => false,
                            'properties' => [
                                'currency' => ['type' => 'string'],
                                'min' => ['type' => 'integer', 'minimum' => 0],
                                'max' => ['type' => 'integer', 'minimum' => 0],
                                'insurance_savings' => ['type' => 'integer', 'minimum' => 0],
                            ],
                            'required' => ['currency', 'min', 'max', 'insurance_savings'],
                        ],
                        'next_steps' => [
                            'type' => 'array',
                            'items' => ['type' => 'string'],
                            'minItems' => 3,
                            'maxItems' => 4,
                        ],
                        'warning_signs' => [
                            'type' => 'array',
                            'items' => ['type' => 'string'],
                            'minItems' => 3,
                            'maxItems' => 4,
                        ],
                        'follow_up_questions' => [
                            'type' => 'array',
                            'items' => ['type' => 'string'],
                            'minItems' => 3,
                            'maxItems' => 4,
                        ],
                        'matched_signals' => [
                            'type' => 'array',
                            'items' => ['type' => 'string'],
                            'maxItems' => 5,
                        ],
                    ],
                    'required' => [
                        'category',
                        'summary',
                        'severity',
                        'confidence',
                        'triage',
                        'recommendation',
                        'estimated_cost',
                        'next_steps',
                        'warning_signs',
                        'follow_up_questions',
                        'matched_signals',
                    ],
                ],
            ],
            'required' => ['reply', 'assessment'],
        ];
    }
}
