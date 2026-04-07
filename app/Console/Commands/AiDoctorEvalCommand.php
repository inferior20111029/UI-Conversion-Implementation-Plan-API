<?php

namespace App\Console\Commands;

use App\Services\AiDoctor\AiDoctorEvaluationService;
use Illuminate\Console\Command;

class AiDoctorEvalCommand extends Command
{
    protected $signature = 'ai-doctor:eval
        {suite=baseline : The eval suite name under resources/ai-doctor/evals}
        {--provider=config : config, fallback, or openai}';

    protected $description = 'Run AI doctor evaluation cases against the current consultation pipeline.';

    public function __construct(
        private readonly AiDoctorEvaluationService $evaluationService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $provider = (string) $this->option('provider');

        if (! in_array($provider, ['config', 'fallback', 'openai'], true)) {
            $this->error('The --provider option must be config, fallback, or openai.');

            return self::INVALID;
        }

        $result = $this->evaluationService->runSuite(
            (string) $this->argument('suite'),
            $provider,
        );

        $rows = collect($result['results'])
            ->map(fn (array $item): array => [
                $item['id'],
                $item['actual']['provider'] ?? '-',
                $item['expected']['category'] ?? '-',
                $item['actual']['category'] ?? '-',
                $item['expected']['severity'] ?? '-',
                $item['actual']['severity'] ?? '-',
                $item['passed'] ? 'PASS' : 'FAIL',
            ])
            ->all();

        $this->info(sprintf(
            'Suite: %s | Provider mode: %s',
            $result['suite'],
            $result['provider'],
        ));

        $this->table(
            ['Case', 'Provider', 'Expected category', 'Actual category', 'Expected severity', 'Actual severity', 'Result'],
            $rows,
        );

        foreach ($result['results'] as $item) {
            if ($item['passed']) {
                continue;
            }

            $this->warn($item['id'].': '.implode(' | ', $item['mismatches']));
        }

        $summary = $result['summary'];
        $this->line(sprintf(
            'Summary: %d/%d passed, %d failed.',
            $summary['passed'],
            $summary['total'],
            $summary['failed'],
        ));

        return $summary['failed'] === 0 ? self::SUCCESS : self::FAILURE;
    }
}
