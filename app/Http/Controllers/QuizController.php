<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Attempt;
use App\Models\Question;
use App\Models\Option;
use App\Models\Answer;
use App\Services\QuizEvaluatorService;

class QuizController extends Controller {


    public function createQuiz(Request $request) {
        $request->validate([
            'title'     => 'required|string',
            'questions' => 'required|array',
        ]);

        $quiz = Quiz::create([
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        foreach ($request->questions as $q) {
            $imagePath = null;
            if (!empty($q['image'])) {
                $imagePath = $q['image']->store('questions', 'public');
            }

            $question = Question::create([
                'quiz_id'       => $quiz->id,
                'type'          => $q['type'],
                'question_text' => $q['question_text'],
                'image_path'    => $imagePath,
                'video_url'     => $q['video_url'] ?? null,
                'marks'         => $q['marks'] ?? 1,
            ]);

            if (in_array($q['type'], ['single_choice', 'multiple_choice', 'binary'])) {
                foreach ($q['options'] ?? [] as $opt) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $opt['text'] ?? null,
                        'image_path'  => $opt['image_path'] ?? null,
                        'is_correct'  => $opt['is_correct'] ?? false,
                    ]);
                }
            }


            if (in_array($q['type'], ['number', 'text']) && !empty($q['correct_answer'])) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $q['correct_answer'],
                    'is_correct'  => true,
                ]);
            }
        }

        return response()->json($quiz->load('questions.options'), 201);
    }


    public function submitAttempt(Request $request) {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'answers' => 'required|array',
        ]);

        $attempt = Attempt::create(['quiz_id' => $request->quiz_id, 'score' => 0]);

        foreach ($request->answers as $ans) {
            Answer::create([
                'attempt_id'  => $attempt->id,
                'question_id' => $ans['question_id'],
                'answer_text' => $ans['answer_text'],
            ]);
        }

        $score = (new QuizEvaluatorService())->evaluate($attempt);

        return response()->json(['attempt_id' => $attempt->id, 'score' => $score]);
    }


    public function getResult($id) {
        $attempt = Attempt::with('answers.question.options')->findOrFail($id);
        return response()->json(['score' => $attempt->score, 'details' => $attempt]);
    }


    public function index() {
        $quizzes = Quiz::all();
        return view('quizzes.index', compact('quizzes'));
    }


    public function show($id) {
        $quiz = Quiz::with('questions.options')->findOrFail($id);
        return view('quizzes.show', compact('quiz'));
    }

    public function attempt(Request $request) {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'answers' => 'required|array',
        ]);

        $attempt = Attempt::create(['quiz_id' => $request->quiz_id, 'score' => 0]);

        foreach ($request->answers as $questionId => $answerText) {
            Answer::create([
                'attempt_id'  => $attempt->id,
                'question_id' => $questionId,
                'answer_text' => is_array($answerText) ? implode(',', $answerText) : $answerText,
            ]);
        }

        $score = (new QuizEvaluatorService())->evaluate($attempt);

        return redirect()->route('quiz.result', $attempt->id);
    }


    public function result($id) {
        $attempt = Attempt::with('answers.question.options')->findOrFail($id);
        $totalMarks = $attempt->quiz->questions->sum('marks');
        return view('quizzes.result', compact('attempt', 'totalMarks'));
    }

    public function create() {
    return view('quizzes.create');
    }
}