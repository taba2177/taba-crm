<?php

namespace App\Filament\Widgets;

use App\Models\LocalReview;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SurveyAnswersChart extends ChartWidget
{
    protected ?string $heading = 'تحليلات إجابات الاستبيان';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public ?string $filter = '30d';

    protected function getFilters(): ?array
    {
        return [
            '7d' => 'آخر 7 أيام',
            '30d' => 'آخر 30 يوماً',
            'all' => 'الكل',
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'enabled' => true,
                    'callbacks' => [
                        // Chart.js v3+ uses callbacks as JS functions; we rely on defaults.
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'stacked' => true,
                ],
                'y' => [
                    'stacked' => true,
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $questions = config('survey.questions') ?? [];
        // Collect all distinct option texts across all questions
        $optionTexts = [];
        $optionColorMap = [];
        foreach ($questions as $q) {
            foreach (($q['options'] ?? []) as $opt) {
                $text = (string)($opt['text'] ?? '');
                if ($text === '') { continue; }
                $optionTexts[$text] = true;
                $score = (int)($opt['score'] ?? 0);
                // Map to color by score
                $optionColorMap[$text] = $score > 0 ? '#10b981' : ($score < 0 ? '#ef4444' : '#f59e0b');
            }
        }
        $optionTexts = array_keys($optionTexts); // unique list

        // Labels are question IDs (short) to keep chart readable
        $labels = array_map(function ($q) {
            return $q['id'] ?? ($q['question'] ?? 'سؤال');
        }, $questions);

        // Prepare counts per [optionText][questionIndex]
        $counts = [];
        foreach ($optionTexts as $optText) {
            $counts[$optText] = array_fill(0, count($questions), 0);
        }

        // Date filter
        $query = LocalReview::query();
        if ($this->filter === '7d') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($this->filter === '30d') {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        // Pull only necessary columns
        $reviews = $query->select(['id', 'survey_answers', 'created_at'])->get();

        foreach ($reviews as $review) {
            $answers = $review->survey_answers;
            if (!is_array($answers)) {
                $decoded = json_decode((string) $answers, true);
                $answers = is_array($decoded) ? $decoded : [];
            }
            foreach ($questions as $qi => $q) {
                $qid = $q['id'] ?? null;
                if ($qid === null) { continue; }
                $answerText = $answers[$qid] ?? null;
                if ($answerText && isset($counts[$answerText])) {
                    $counts[$answerText][$qi] += 1;
                }
            }
        }

        // Build datasets (stacked): one dataset per answer option label
        $datasets = [];
        foreach ($optionTexts as $optText) {
            $datasets[] = [
                'label' => $optText,
                'data' => $counts[$optText] ?? [],
                'backgroundColor' => $optionColorMap[$optText] ?? '#93c5fd',
                'borderWidth' => 0,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }
}