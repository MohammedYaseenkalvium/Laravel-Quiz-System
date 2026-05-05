<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Attempt;
use App\Models\Question;
use App\Models\Quiz;
use App\QuestionTypes\QuestionTypeRegistry;
use App\Services\QuizEvaluatorService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(
        private QuestionTypeRegistry $registry,
        private QuizEvaluatorService $evaluator,
    ) {}

    // ── API ───────────────────────────────────────────────────────────────────

    public function createQuiz(Request $request)
    {
        $request->validate([
            'title'     => 'required|string',
            'questions' => 'required|array',
            'questions.*.type' => ['required', 'string', 'in:' . implode(',', $this->registry->keys())],
        ]);

        $quiz = Quiz::create([
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        foreach ($request->questions as $q) {
            $imagePath = null;
            if (!empty($q['image']) && $q['image'] instanceof \Illuminate\Http\UploadedFile) {
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

            // Delegate option saving to the type handler — no in_array check needed
            $this->registry->get($q['type'])->saveOptions($question, $q);
        }

        return response()->json($quiz->load('questions.options'), 201);
    }

    public function submitAttempt(Request $request)
    {
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

        $score = $this->evaluator->evaluate($attempt);

        return response()->json(['attempt_id' => $attempt->id, 'score' => $score]);
    }

    public function getResult($id)
    {
        $attempt = Attempt::with('answers.question.options')->findOrFail($id);
        return response()->json(['score' => $attempt->score, 'details' => $attempt]);
    }

    // ── Web ───────────────────────────────────────────────────────────────────

    public function index()
    {
        $quizzes = Quiz::all();
        return view('quizzes.index', compact('quizzes'));
    }

    public function show($id)
    {
        $quiz = Quiz::with('questions.options')->findOrFail($id);

        // Pass registry to the view so it can render inputs without its own hardcoding
        $registry = $this->registry;

        return view('quizzes.show', compact('quiz', 'registry'));
    }

    public function attempt(Request $request)
    {
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

        $this->evaluator->evaluate($attempt);

        return redirect()->route('quiz.result', $attempt->id);
    }

    public function result($id)
    {
        $attempt    = Attempt::with('answers.question.options')->findOrFail($id);
        $totalMarks = $attempt->quiz->questions->sum('marks');
        $registry   = $this->registry;

        return view('quizzes.result', compact('attempt', 'totalMarks', 'registry'));
    }

    public function create()
    {
        // Pass type metadata to the view as JSON so JS needs no hardcoded list
        $types = collect($this->registry->all())->map(fn($t) => [
            'key'       => $t->key(),
            'label'     => $t->label(),
            'hasOptions'=> $t->hasOptions(),
            'inputType' => $t->inputType(),
        ])->values();

        return view('quizzes.create', compact('types'));
    }
}