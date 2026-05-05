<?php

namespace App\Services;

use App\Models\Attempt;
use App\QuestionTypes\QuestionTypeRegistry;

class QuizEvaluatorService
{
    public function __construct(private QuestionTypeRegistry $registry) {}

    public function evaluate(Attempt $attempt): int
    {
        $score = 0;

        foreach ($attempt->answers as $answer) {
            $question = $answer->question;
            $question->load('options');

            
            $typeHandler = $this->registry->get($question->type);
            $score += $typeHandler->evaluate($question, $answer);
        }

        $attempt->update(['score' => $score]);
        return $score;
    }
}