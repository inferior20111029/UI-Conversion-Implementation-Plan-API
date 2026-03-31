<?php

namespace App\Console\Commands;

use App\Models\Pet;
use App\Services\RiskScoreService;
use Illuminate\Console\Command;

class CalculateInsurance extends Command
{
    protected $signature = 'insurance:calculate {petId? : 指定寵物 ID（不填則計算全部）}';

    protected $description = '計算寵物保險風險分數與保費優惠';

    public function handle(RiskScoreService $riskScoreService)
    {
        $petId = $this->argument('petId');

        if ($petId) {
            $pets = Pet::with(['healthRecords', 'insuranceProfile'])->where('id', $petId)->get();
        } else {
            $pets = Pet::with(['healthRecords', 'insuranceProfile'])->get();
        }

        if ($pets->isEmpty()) {
            $this->warn('找不到符合條件的寵物。');
            return 0;
        }

        $this->info("開始計算 {$pets->count()} 隻寵物的風險分數...");
        $bar = $this->output->createProgressBar($pets->count());
        $bar->start();

        foreach ($pets as $pet) {
            $profile = $riskScoreService->calculate($pet);
            $this->line("\n  🐾 {$pet->name} → 風險分數: {$profile->risk_score}，保費折扣: {$profile->premium_discount}%");
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('✅ 全部計算完成！');

        return 0;
    }
}
