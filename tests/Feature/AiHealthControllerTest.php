<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiHealthControllerTest extends TestCase
{
    public function test_image_is_forwarded_to_ai_health_service(): void
    {
        config([
            'services.ai_health.endpoint' => 'http://127.0.0.1:8000/predict',
        ]);

        Http::fake([
            'http://127.0.0.1:8000/predict' => Http::response([
                'label' => 'healthy',
                'confidence' => 0.98,
            ]),
        ]);

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->post('/api/ai-health/analyze', [
                'image' => UploadedFile::fake()->image('test.png'),
            ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'label' => 'healthy',
                    'confidence' => 0.98,
                ],
            ]);

        Http::assertSent(fn ($request) => $request->url() === 'http://127.0.0.1:8000/predict');
    }

    public function test_image_is_required_for_ai_health_analysis(): void
    {
        config([
            'services.ai_health.endpoint' => 'http://127.0.0.1:8000/predict',
        ]);

        Http::fake();

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->post('/api/ai-health/analyze', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);

        Http::assertNothingSent();
    }
}
