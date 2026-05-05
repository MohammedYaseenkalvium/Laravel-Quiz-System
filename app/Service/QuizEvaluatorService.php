<?php

namespace App\Services;

class QuizEvaluatorService
{
    public function evaluate($attempt)
    {
        $score =0;

        foreach($attempt->answers as $ans){
            $q = $ans->question;
            if ($q->type === 'single_choice'){
                $correctOption = $q->options->where('is_correct', true)->first();

                if($correctOption && $correctOption->option_text === $ans->answer_text){
                    $score += $q->marks;
                }
            }

            elseif ($q->type === 'multiple_choice'){
                $correctOptions = $q->options->where('is_correct',true)->pluck('option_text')->toArray();
                $userAnswers = explode(',', $ans->answer_text);

                sort($correctOptions);
                sort($userAnswers);

                if($correctOptions === $userAnswers){
                    $score += $q->marks;
                }
            }

            elseif ($q->type === 'text'){
                if (strtolower(trim($ans->answer_text)) === '4'){
                    $score += $q->marks;
                }
            }

            elseif ($q->type === 'binary') {
                $correctOption = $q->options->where('is_correct', true)->first();

                if ($correctOption && strtolower($correctOption->option_text) === strtolower($ans->answer_text)) {
                    $score += $q->marks;
                }
            }

            elseif ($q->type === 'number') {
                $correctOption = $q->options->where('is_correct', true)->first();

                if ($correctOption && $correctOption->option_text == $ans->answer_text) {
                    $score += $q->marks;
                }
            }



            
        }
        $attempt->update(['score' => $score]);

        return $score;
    }
}