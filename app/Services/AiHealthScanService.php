<?php

namespace App\Services;

use App\Jobs\ProcessAiHealthScanJob;
use App\Models\AiHealthScan;
use App\Models\Pet;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class AiHealthScanService
{
    public function create(Pet $pet, UploadedFile $file): AiHealthScan
    {
        return DB::transaction(function () use ($pet, $file) {
            $imagePath = $file->store('ai-health', 'public');

            $scan = AiHealthScan::create([
                'pet_id' => $pet->id,
                'image_path' => $imagePath,
                'status' => AiHealthScan::STATUS_PROCESSING,
            ]);

            ProcessAiHealthScanJob::dispatch($scan->id)->afterCommit();

            return $scan;
        });
    }
}
