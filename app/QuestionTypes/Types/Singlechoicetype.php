<?php

namespace App\QuestionTypes\Types;

use App\Models\Answer;
use App\Models\Option;
use App\Models\Question;
use App\QuestionTypes\QuestionTypeInterface;

class SingleChoiceType implements QuestionTypeInterface
{
    public function key(): string   { return 'single_choice'; }
    public function label(): string { return 'Single Choice'; }
    public function hasOptions(): bool { return true; }
    public function inputType(): string { return 'radio'; }

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
        $correct = $question->options->firstWhere('is_correct', true);
        if (!$correct) return 0;

        return strtolower(trim($correct->option_text)) === strtolower(trim($answer->answer_text))
            ? $question->marks
            : 0;
    }
}