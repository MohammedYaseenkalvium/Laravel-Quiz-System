<?php

namespace App\QuestionTypes\Types;

use App\Models\Answer;
use App\Models\Option;
use App\Models\Question;
use App\QuestionTypes\QuestionTypeInterface;

class NumberType implements QuestionTypeInterface
{
    public function key(): string   { return 'number'; }
    public function label(): string { return 'Number Input'; }
    public function hasOptions(): bool { return false; }
    public function inputType(): string { return 'number'; }

    public function saveOptions(Question $question, array $data): void
    {
        if (!empty($data['correct_answer'])) {
            Option::create([
                'question_id' => $question->id,
                'option_text' => $data['correct_answer'],
                'is_correct'  => true,
            ]);
        }
    }

    public function evaluate(Question $question, Answer $answer): int
    {
        $correct = $question->options->firstWhere('is_correct', true);
        if (!$correct) return 0;

        return (float) $correct->option_text === (float) $answer->answer_text
            ? $question->marks
            : 0;
    }
}