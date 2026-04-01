<?php

namespace App\Jobs;

use App\Models\AiHealthScan;
use App\Services\AiHealthAnalyzerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessAiHealthScanJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $timeout = 120;

    public function __construct(private readonly int $scanId) {}

    public function handle(AiHealthAnalyzerService $analyzer): void
    {
        $scan = AiHealthScan::query()->with('pet')->find($this->scanId);

        if (! $scan) {
            return;
        }

        $analysis = $analyzer->analyzeScan($scan);

        $scan->update([
            'status' => AiHealthScan::STATUS_COMPLETED,
            'confidence' => $analysis['confidence'],
            'score' => $analysis['score'],
            'estimated_saving' => $analysis['estimated_saving'],
            'issues' => $analysis['issues'],
            'scanned_at' => now(),
        ]);
    }

    public function failed(Throwable $exception): void
    {
        $scan = AiHealthScan::find($this->scanId);

        if (! $scan) {
            return;
        }

        $scan->update([
            'status' => AiHealthScan::STATUS_FAILED,
            'scanned_at' => now(),
        ]);
    }
}
