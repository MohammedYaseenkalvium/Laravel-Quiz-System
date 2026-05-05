<?php

namespace App\QuestionTypes\Types;

use App\Models\Answer;
use App\Models\Option;
use App\Models\Question;
use App\QuestionTypes\QuestionTypeInterface;

class MultipleChoiceType implements QuestionTypeInterface
{
    public function key(): string   { return 'multiple_choice'; }
    public function label(): string { return 'Multiple Choice'; }
    public function hasOptions(): bool { return true; }
    public function inputType(): string { return 'checkbox'; }

    public function saveOptions(Question $question, array $data): void
    {
        foreach ($data['options'] ?? [] as $opt) {
            Option::create([
                'question_id' => $question->id,
                'option_text' => $opt['text'] ?? null,
                'image_path'  => $opt['image_path'] ?? null,
                'is_correct'  => (bool) ($opt['is_correct'] ?? false),
            ]);
        }
    }

    public function evaluate(Question $question, Answer $answer): int
    {
        $correctOptions = $question->options
            ->where('is_correct', true)
            ->pluck('option_text')
            ->map(fn($v) => strtolower(trim($v)))
            ->sort()
            ->values()
            ->toArray();

        $userAnswers = collect(explode(',', $answer->answer_text))
            ->map(fn($v) => strtolower(trim($v)))
            ->sort()
            ->values()
            ->toArray();

        return $correctOptions === $userAnswers ? $question->marks : 0;
    }
}