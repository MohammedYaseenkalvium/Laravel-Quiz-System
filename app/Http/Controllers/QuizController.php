<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Attempt;
use App\Models\Question;
use App\Models\Option;
use App\Models\Answer;
use App\Services\QuizEvaluatorService;


class QuizController extends Controller
{
    public function test()
    {
        $quiz = Quiz::create([
            'title'=>'Math Quiz',
            'description'=>'A simple math quiz',
        ]);

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'type' => 'single_choice',
            'question_text' => '2 + 2 = ?',
            'marks' => 1
        ]);

        Option::create([
            'question_id' => $question->id,
            'option_text' => '3',
            'is_correct' => false
        ]);

        Option::create([
            'question_id'=>$question->id,
            'option_text'=>'4',
            'is_correct'=>true
        ]);

        $attempt = Attempt::create([
            'quiz_id' => $quiz->id,
            'score' => 0
        ]);

        Answer::create([
            'attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'answer_text' => '4'
        ]);

        $evaluator = new QuizEvaluatorService();
        $score = $evaluator->evaluate($attempt);

        return [
            'score'=> $score,
            'quiz'=>$quiz->load('questions.options')
        ];
    }

    public function createQuiz(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'questions'=>'required|array'
        ]);

        $quiz = Quiz::create([
            'title'=> $request->title,
            'description'=> $request->description
        ]);

        foreach($request->questions as $q){
            $question = Question::create([
                'quiz_id'=>$quiz->id,
                'type'=>$q['type'],
                'question_text'=>$q['question_text'],
                'marks'=>$q['marks'] ?? 1
                ]);

            if (isset($q['options'])){
                foreach($q['options'] as $opt){
                    Option::create([
                        'question_id'=>$question->id,
                        'option_text'=>$opt['text'],
                        'is_correct'=>$opt['is_correct'] ?? false
                    ]);
                }
            }
        }
        return $quiz->load('questions.options');
    } 

    public function submitAttempt(Request $request)
{
    $request->validate([
        'quiz_id' => 'required|exists:quizzes,id',
        'answers' => 'required|array'
    ]);

    $attempt = Attempt::create([
        'quiz_id' => $request->quiz_id,
        'score' => 0
    ]);

    foreach ($request->answers as $ans) {
        Answer::create([
            'attempt_id' => $attempt->id,
            'question_id' => $ans['question_id'],
            'answer_text' => $ans['answer_text']
        ]);
    }

    $evaluator = new QuizEvaluatorService();
    $score = $evaluator->evaluate($attempt);

    return [
        'attempt_id' => $attempt->id,
        'score' => $score
    ];
}

    public function getResult($id)
    {
        $attempt = Attempt::with('answers.question.options')->findOrFail($id);

        return [
            'score'=>$attempt->score,
            'details'=>$attempt
        ];
    }
}
