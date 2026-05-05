<?php

namespace App\QuestionTypes\Types;

use App\Models\Answer;
use App\Models\Option;
use App\Models\Question;
use App\QuestionTypes\QuestionTypeInterface;

class BinaryType implements QuestionTypeInterface
{
    public function key(): string   { return 'binary'; }
    public function label(): string { return 'Yes / No'; }
    public function hasOptions(): bool { return true; }
    public function inputType(): string { return 'radio'; }

    public function saveOptions(Question $question, array $data): void
    {
        // Always force Yes/No options; ignore whatever was sent
        Option::create(['question_id' => $question->id, 'option_text' => 'Yes', 'is_correct' => false]);
        Option::create(['question_id' => $question->id, 'option_text' => 'No',  'is_correct' => false]);

        // Mark whichever the creator flagged as correct
        foreach ($data['options'] ?? [] as $opt) {
            if (!empty($opt['is_correct'])) {
                $question->options()
                    ->where('option_text', $opt['text'])
                    ->update(['is_correct' => true]);
            }
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