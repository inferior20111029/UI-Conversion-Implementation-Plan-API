<?php

namespace App\Services;

use App\Models\HealthRecord;
use App\Models\Pet;
use Illuminate\Support\Collection;

class AiDoctorConsultationService
{
    private const CATEGORY_RULES = [
        'digestive' => [
            'label' => '消化腸胃',
            'keywords' => ['vomit', 'diarrhea', 'low_appetite', 'stomach_pain'],
            'follow_up' => ['症狀從什麼時候開始？', '今天有喝水嗎？有沒有吐超過一次？', '糞便顏色或頻率有明顯改變嗎？'],
            'next_steps' => ['先少量多次補充水分，暫時避免過量零食。', '記錄嘔吐或腹瀉次數、食慾與精神狀態。', '若 24 小時內未改善，安排門診評估。'],
            'warning_signs' => ['持續嘔吐或腹瀉超過 6 到 8 小時', '吐血、黑便或嚴重脫水', '完全不進食且精神明顯下滑'],
            'cost' => [
                'low' => [400, 1200],
                'medium' => [1200, 3200],
                'high' => [3500, 9000],
            ],
        ],
        'skin' => [
            'label' => '皮膚與耳朵',
            'keywords' => ['itchy_skin', 'ear_issue', 'ear_odor', 'skin_redness', 'hair_loss'],
            'follow_up' => ['抓癢是突然開始，還是已經持續好幾天？', '耳朵或皮膚有沒有紅、腫、滲液或結痂？', '最近有洗澡、美容、換飼料或外出草地嗎？'],
            'next_steps' => ['避免再刺激患部，先不要自行使用人類藥膏。', '拍下患部與耳朵狀態，記錄抓癢頻率與位置。', '若有持續紅腫、異味或分泌物，安排門診檢查。'],
            'warning_signs' => ['耳朵劇烈疼痛、頭歪或一直甩頭', '皮膚快速紅腫擴大或滲血', '抓到破皮仍持續惡化'],
            'cost' => [
                'low' => [500, 1500],
                'medium' => [1500, 3500],
                'high' => [3500, 8000],
            ],
        ],
        'respiratory' => [
            'label' => '呼吸道',
            'keywords' => ['cough', 'sneeze', 'breathing_issue'],
            'follow_up' => ['呼吸變快是在休息時也持續嗎？', '有沒有出現咳嗽、張口呼吸或舌頭發紫？', '症狀是在運動後才出現，還是靜止時也有？'],
            'next_steps' => ['先保持環境通風與安靜，避免劇烈活動。', '觀察休息時呼吸頻率與是否有咳嗽加重。', '若症狀持續或加劇，盡快就醫檢查。'],
            'warning_signs' => ['張口呼吸或呼吸明顯費力', '舌頭、牙齦顏色變淡或發紫', '精神快速變差、站立困難'],
            'cost' => [
                'low' => [800, 1800],
                'medium' => [1800, 4500],
                'high' => [4500, 12000],
            ],
        ],
        'mobility' => [
            'label' => '關節與行動',
            'keywords' => ['limp', 'joint_pain', 'pain'],
            'follow_up' => ['是哪一隻腳或哪個部位不舒服？', '是活動後變明顯，還是休息時也會痛？', '有跌倒、跳高或激烈運動過嗎？'],
            'next_steps' => ['先減少跑跳與上下樓，避免再次拉扯。', '觀察是否有腫脹、熱感或碰觸就疼痛。', '若超過 24 小時仍跛腳，安排骨骼關節評估。'],
            'warning_signs' => ['完全不願意站立或承重', '關節明顯腫脹變形', '疼痛快速惡化或伴隨尖叫'],
            'cost' => [
                'low' => [800, 2000],
                'medium' => [2000, 4500],
                'high' => [5000, 15000],
            ],
        ],
        'urinary' => [
            'label' => '泌尿排泄',
            'keywords' => ['urine_issue', 'blood_urine'],
            'follow_up' => ['跑廁所的次數有變多嗎？', '每次有真的排出尿，還是一直蹲卻沒有？', '尿液顏色、氣味或精神有改變嗎？'],
            'next_steps' => ['先確認有沒有正常喝水與排尿。', '記錄排尿次數、每次尿量與有無疼痛反應。', '若排尿明顯困難，應盡快安排門診。'],
            'warning_signs' => ['一直用力卻尿不出來', '血尿或明顯疼痛哀叫', '同時伴隨嘔吐、腹脹或精神快速變差'],
            'cost' => [
                'low' => [1000, 2500],
                'medium' => [2500, 5000],
                'high' => [5000, 15000],
            ],
        ],
        'general' => [
            'label' => '一般健康觀察',
            'keywords' => ['low_energy', 'fever', 'weight_loss'],
            'follow_up' => ['精神不好是突然發生，還是已經持續一段時間？', '最近食慾、飲水、排便排尿有改變嗎？', '有沒有出現其他一起發生的症狀？'],
            'next_steps' => ['先補充更完整的症狀、持續時間與頻率。', '記錄食慾、喝水量、排便與活動力。', '如果 24 小時內沒有改善，安排門診。'],
            'warning_signs' => ['完全不進食超過 24 小時', '呼吸、走路或意識變差', '症狀在幾小時內快速惡化'],
            'cost' => [
                'low' => [300, 1000],
                'medium' => [1000, 2500],
                'high' => [3000, 9000],
            ],
        ],
    ];

    private const SIGNAL_LABELS = [
        'vomit' => '嘔吐',
        'diarrhea' => '腹瀉',
        'low_appetite' => '食慾下降',
        'stomach_pain' => '腸胃不適',
        'itchy_skin' => '抓癢',
        'ear_issue' => '耳朵不適',
        'ear_odor' => '耳朵異味',
        'skin_redness' => '皮膚紅腫',
        'hair_loss' => '掉毛',
        'cough' => '咳嗽',
        'sneeze' => '打噴嚏',
        'breathing_issue' => '呼吸異常',
        'limp' => '跛腳',
        'joint_pain' => '關節疼痛',
        'pain' => '疼痛',
        'urine_issue' => '排尿異常',
        'blood_urine' => '血尿',
        'low_energy' => '精神差',
        'fever' => '發熱',
        'weight_loss' => '體重下降',
        'persistent' => '持續超過一天',
        'worsening' => '症狀惡化',
        'loss_of_appetite' => '完全不吃',
        'high_pain' => '疼痛加劇',
        'frequent' => '頻率增加',
        'breathing_emergency' => '呼吸困難',
        'seizure' => '抽搐',
        'collapse' => '昏倒',
        'urinary_block' => '尿不出來',
    ];

    private const EMERGENCY_KEYWORDS = [
        'breathing_emergency',
        'seizure',
        'collapse',
        'urinary_block',
    ];

    private const ESCALATION_KEYWORDS = [
        'persistent',
        'worsening',
        'loss_of_appetite',
        'high_pain',
        'frequent',
        'blood_urine',
    ];

    public function consult(Pet $pet, string $message, array $history = []): array
    {
        $conversationText = $this->conversationText($message, $history);
        $matchedCategories = $this->matchedCategories($conversationText);
        $categoryKey = $this->primaryCategoryKey($matchedCategories);
        $category = self::CATEGORY_RULES[$categoryKey];
        $severity = $this->severity($conversationText, $matchedCategories, $pet);
        $triage = $this->triage($severity);
        $confidence = $this->confidence($matchedCategories, $severity, $message);
        $estimatedCost = $this->estimatedCost($categoryKey, $severity);
        $recentRecords = $this->recentRecords($pet);
        $matchedSignals = $this->matchedSignals($matchedCategories, $categoryKey);
        $recommendation = $this->recommendation($pet, $category['label'], $severity, $category['next_steps'][0], $recentRecords);

        return [
            'reply' => $this->reply($pet, $category['label'], $severity, $recommendation, $matchedSignals),
            'assessment' => [
                'category' => $categoryKey,
                'summary' => $category['label'],
                'severity' => $severity,
                'confidence' => $confidence,
                'triage' => $triage,
                'recommendation' => $recommendation,
                'estimated_cost' => [
                    'currency' => 'TWD',
                    'min' => $estimatedCost[0],
                    'max' => $estimatedCost[1],
                    'insurance_savings' => (int) round((($estimatedCost[0] + $estimatedCost[1]) / 2) * 0.2),
                ],
                'next_steps' => $this->mergeUnique(
                    $category['next_steps'],
                    $severity === 'high'
                        ? ['建議今天就安排就醫，不要只靠居家觀察。']
                        : ($severity === 'medium'
                            ? ['若症狀持續到明天或有惡化，請安排門診。']
                            : ['可以先短時間觀察，但要持續記錄變化。'])
                ),
                'warning_signs' => $this->mergeUnique(
                    $category['warning_signs'],
                    $severity === 'high' ? ['若出現呼吸、意識或排尿困難，應立即就醫。'] : []
                ),
                'follow_up_questions' => $this->mergeUnique(
                    $category['follow_up'],
                    $recentRecords->isEmpty() ? ['最近有沒有新的健康紀錄或用藥資訊可以補充？'] : []
                ),
                'matched_signals' => $matchedSignals,
            ],
            'pet' => [
                'id' => $pet->id,
                'name' => $pet->name,
                'type' => $pet->type,
                'breed' => $pet->breed,
                'weight' => $pet->weight,
                'age_years' => $pet->birthday?->floatDiffInYears(now()),
                'recent_records' => $recentRecords->values()->all(),
            ],
            'meta' => [
                'generated_at' => now()->toISOString(),
                'disclaimer' => 'AI 建議僅供初步分級參考，不能取代獸醫師正式診斷。',
            ],
        ];
    }

    private function conversationText(string $message, array $history): string
    {
        $historyText = collect($history)
            ->filter(fn (array $item): bool => ($item['role'] ?? null) === 'user')
            ->pluck('content')
            ->take(-3)
            ->implode(' ');

        return strtolower(trim($historyText.' '.$message));
    }

    private function matchedCategories(string $text): array
    {
        $matches = [];

        foreach (self::CATEGORY_RULES as $key => $rule) {
            $matchedKeywords = [];

            foreach ($rule['keywords'] as $keyword) {
                if ($this->contains($text, $keyword)) {
                    $matchedKeywords[] = $keyword;
                }
            }

            if ($matchedKeywords !== []) {
                $matches[$key] = $matchedKeywords;
            }
        }

        return $matches;
    }

    private function primaryCategoryKey(array $matchedCategories): string
    {
        if ($matchedCategories === []) {
            return 'general';
        }

        uasort($matchedCategories, fn (array $left, array $right): int => count($right) <=> count($left));

        return (string) array_key_first($matchedCategories);
    }

    private function severity(string $text, array $matchedCategories, Pet $pet): string
    {
        foreach (self::EMERGENCY_KEYWORDS as $keyword) {
            if ($this->contains($text, $keyword)) {
                return 'high';
            }
        }

        $score = 0;

        foreach (self::ESCALATION_KEYWORDS as $keyword) {
            if ($this->contains($text, $keyword)) {
                $score++;
            }
        }

        $score += count($matchedCategories) >= 2 ? 1 : 0;
        $score += collect($matchedCategories)->sum(fn (array $keywords) => count($keywords) >= 2 ? 1 : 0);
        $score += $this->isSenior($pet) ? 1 : 0;

        if ($score >= 4) {
            return 'high';
        }

        if ($score >= 2) {
            return 'medium';
        }

        return 'low';
    }

    private function triage(string $severity): string
    {
        return match ($severity) {
            'high' => '建議今天盡快安排就醫或急診評估',
            'medium' => '建議 24 小時內安排門診或持續密切觀察',
            default => '可先居家觀察並持續記錄',
        };
    }

    private function confidence(array $matchedCategories, string $severity, string $message): int
    {
        $base = match ($severity) {
            'high' => 86,
            'medium' => 80,
            default => 72,
        };

        $signalCount = collect($matchedCategories)->flatten()->count();
        $lengthBonus = mb_strlen(trim($message)) > 20 ? 4 : 0;

        return max(62, min(97, $base + min(8, $signalCount * 2) + $lengthBonus));
    }

    private function estimatedCost(string $categoryKey, string $severity): array
    {
        $category = self::CATEGORY_RULES[$categoryKey] ?? self::CATEGORY_RULES['general'];

        return $category['cost'][$severity] ?? self::CATEGORY_RULES['general']['cost'][$severity];
    }

    private function recommendation(
        Pet $pet,
        string $categoryLabel,
        string $severity,
        string $primaryNextStep,
        Collection $recentRecords,
    ): string {
        $recordHint = $recentRecords->isNotEmpty()
            ? '我也有參考最近的健康紀錄。'
            : '如果有新的用藥、飲食或症狀照片，可以一起補充。';

        return match ($severity) {
            'high' => "{$pet->name} 目前比較像高風險的 {$categoryLabel} 問題，請不要只靠觀察，建議今天就盡快讓獸醫評估。{$recordHint}",
            'medium' => "{$pet->name} 目前偏向中度的 {$categoryLabel} 狀況，建議先觀察關鍵變化並安排近 24 小時內評估。{$recordHint}",
            default => "{$pet->name} 目前較像輕度 {$categoryLabel}，可以先從這一步開始：{$primaryNextStep} {$recordHint}",
        };
    }

    private function reply(
        Pet $pet,
        string $categoryLabel,
        string $severity,
        string $recommendation,
        array $matchedSignals,
    ): string {
        $signalText = $matchedSignals !== []
            ? '我先抓到的重點包括：'.implode('、', array_slice($matchedSignals, 0, 3)).'。'
            : '目前症狀描述還偏少，我先用保守分級處理。';

        $severityText = match ($severity) {
            'high' => '這次分級偏高風險。',
            'medium' => '這次分級屬於中度注意。',
            default => '這次分級偏低風險。',
        };

        return "{$pet->name} 看起來比較像 {$categoryLabel}。{$signalText} {$severityText} {$recommendation}";
    }

    private function recentRecords(Pet $pet): Collection
    {
        return $pet->healthRecords
            ->sortByDesc('recorded_at')
            ->take(3)
            ->map(fn (HealthRecord $record): array => [
                'type' => $record->type,
                'recorded_at' => optional($record->recorded_at)->toISOString(),
                'summary' => $this->recordSummary($record),
            ])
            ->values();
    }

    private function recordSummary(HealthRecord $record): string
    {
        $value = $record->value;

        if (is_array($value)) {
            $parts = collect($value)
                ->map(fn (mixed $item, string $key): ?string => is_scalar($item) && $item !== ''
                    ? "{$key}: {$item}"
                    : null)
                ->filter()
                ->values()
                ->all();

            return $parts !== [] ? implode(' / ', $parts) : '已更新健康紀錄';
        }

        if (is_string($value)) {
            $trimmed = trim($value);

            if ($trimmed !== '' && $this->looksLikeJson($trimmed)) {
                $decoded = json_decode($trimmed, true);

                if (is_array($decoded)) {
                    $parts = collect($decoded)
                        ->map(fn (mixed $item, string $key): ?string => is_scalar($item) && $item !== ''
                            ? "{$key}: {$item}"
                            : null)
                        ->filter()
                        ->values()
                        ->all();

                    if ($parts !== []) {
                        return implode(' / ', $parts);
                    }
                }
            }

            if ($trimmed !== '') {
                return $trimmed;
            }
        }

        return '已更新健康紀錄';
    }

    private function matchedSignals(array $matchedCategories, string $categoryKey): array
    {
        return array_values(array_map(
            fn (string $signal): string => self::SIGNAL_LABELS[$signal] ?? $signal,
            array_slice(array_unique($matchedCategories[$categoryKey] ?? []), 0, 5)
        ));
    }

    private function contains(string $text, string $keyword): bool
    {
        return str_contains($text, strtolower($keyword));
    }

    private function isSenior(Pet $pet): bool
    {
        return $pet->birthday !== null && $pet->birthday->diffInYears(now()) >= ($pet->type === 'cat' ? 11 : 9);
    }

    private function looksLikeJson(string $value): bool
    {
        return str_starts_with($value, '{') || str_starts_with($value, '[');
    }

    private function mergeUnique(array $primary, array $secondary): array
    {
        return array_values(array_slice(array_unique(array_merge($primary, $secondary)), 0, 4));
    }
}
