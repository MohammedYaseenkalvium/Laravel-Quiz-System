<?php
namespace App\Services;

use App\Models\Attempt;

class QuizEvaluatorService {
    public function evaluate(Attempt $attempt): int {
        $score = 0;

        foreach ($attempt->answers as $ans) {
            $q = $ans->question;
            $q->load('options');

            $score += match($q->type) {
                'single_choice', 'binary' => $this->evaluateChoice($q, $ans->answer_text),
                'multiple_choice'         => $this->evaluateMultiple($q, $ans->answer_text),
                'number'                  => $this->evaluateNumber($q, $ans->answer_text),
                'text'                    => $this->evaluateText($q, $ans->answer_text),
                default                   => 0,
            };
        }

        $attempt->update(['score' => $score]);
        return $score;
    }

    private function evaluateChoice($question, string $answer): int {
        $correct = $question->options->firstWhere('is_correct', true);
        return ($correct && strtolower(trim($correct->option_text)) === strtolower(trim($answer)))
            ? $question->marks : 0;
    }

    private function evaluateMultiple($question, string $answer): int {
        $correctOptions = $question->options->where('is_correct', true)->pluck('option_text')
            ->map(fn($v) => strtolower(trim($v)))->sort()->values()->toArray();

        $userAnswers = collect(explode(',', $answer))
            ->map(fn($v) => strtolower(trim($v)))->sort()->values()->toArray();

        return ($correctOptions === $userAnswers) ? $question->marks : 0;
    }

    private function evaluateNumber($question, string $answer): int {
        $correct = $question->options->firstWhere('is_correct', true);
        return ($correct && (float)$correct->option_text === (float)$answer) ? $question->marks : 0;
    }

    private function evaluateText($question, string $answer): int {
        $correct = $question->options->firstWhere('is_correct', true);
        return ($correct && strtolower(trim($correct->option_text)) === strtolower(trim($answer)))
            ? $question->marks : 0;
    }
}