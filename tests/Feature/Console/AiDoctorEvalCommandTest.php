<?php

namespace Tests\Feature\Console;

use Tests\TestCase;

class AiDoctorEvalCommandTest extends TestCase
{
    public function test_baseline_eval_suite_passes_with_fallback_provider(): void
    {
        config([
            'services.ai_doctor.provider' => 'fallback',
            'services.ai_doctor.api_key' => null,
        ]);

        $this->artisan('ai-doctor:eval baseline --provider=fallback')
            ->expectsOutputToContain('Suite: baseline | Provider mode: fallback')
            ->expectsOutputToContain('Summary: 4/4 passed, 0 failed.')
            ->assertExitCode(0);
    }
}
