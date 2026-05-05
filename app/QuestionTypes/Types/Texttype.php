<?php

namespace App\QuestionTypes\Types;

use App\Models\Answer;
use App\Models\Option;
use App\Models\Question;
use App\QuestionTypes\QuestionTypeInterface;

class TextType implements QuestionTypeInterface
{
    public function key(): string   { return 'text'; }
    public function label(): string { return 'Text Input'; }
    public function hasOptions(): bool { return false; }
    public function inputType(): string { return 'text'; }

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

        return strtolower(trim($correct->option_text)) === strtolower(trim($answer->answer_text))
            ? $question->marks
            : 0;
    }
}