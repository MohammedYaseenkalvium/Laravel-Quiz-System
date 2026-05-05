<?php
use App\Http\Controllers\QuizController;

Route:: get('/test',[QuizController::class,'test']);


// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
// use App\Services\QuizEvaluatorService;
// use App\Models\Quiz;
// use App\Models\Question;
// use App\Models\Option;
// use App\Models\Attempt;
// use App\Models\Answer;

// Route::get('/test', function () {

//     $quiz = Quiz::create([
//         'title' => 'Math Quiz',
//         'description' => 'Basic test'
//     ]);

//     $question = Question::create([
//         'quiz_id' => $quiz->id,
//         'type' => 'single_choice',
//         'question_text' => '2 + 2 = ?',
//         'marks' => 1
//     ]);

//     Option::create([
//         'question_id' => $question->id,
//         'text' => '3',
//         'is_correct' => false
//     ]);

//     Option::create([
//         'question_id' => $question->id,
//         'text' => '4',
//         'is_correct' => true
//     ]);

//     $attempt = Attempt::create([
//         'quiz_id' => $quiz->id,
//         'score' => 0
//     ]);

//     Answer::create([
//         'attempt_id' => $attempt->id,
//         'question_id' => $question->id,
//         'answer_text' => '4',
//     ]);

//     // $score=0;
//     $evaluator = new QuizEvaluatorService();
//     $score = $evaluator->evaluate($attempt);

    // foreach ($attempt->answers as $ans){
    //     $q = $ans->question;

    //     if($q->type ==='single_choice'){
    //         $correctOption = $q->options->where('is_correct', true)->first();

    //         if($correctOption && $correctOption->text === $ans->answer_text){
    //             $score += $q->marks;
    //         }
    //     }

    //     elseif($q->type==='multiple_choice'){
    //         $correctOptions = $q->options->where('is_correct', true)->pluck('text')->toArray();
    //         $userAnswers = explode(',', $ans->answer_text);

    //         sort($correctOptions);
    //         sort($userAnswers);

    //         if($correctOptions === $userAnswers){
    //             $score += $q->marks;
    //         }
    //     }

    //     elseif($q->type==='text'){
    //         if (strtolower(trim($ans->answer_text))==='4'){
    //             $score += $q->marks;
    //         }
    //         }

    // }
    // $attempt->update(['score' => $score]);

    // return [
    //     'score' => $score,
    //     'quiz'=>$quiz->load('questions.options')
    //     ];
    

    
// });