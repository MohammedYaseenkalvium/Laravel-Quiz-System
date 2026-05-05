<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Result</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .score-box { background: #f0f9ff; border: 2px solid #0ea5e9; padding: 30px; text-align: center; border-radius: 10px; margin-bottom: 30px; }
        .score-box h2 { font-size: 2.5rem; color: #0369a1; }
        .answer-row { border: 1px solid #e5e7eb; padding: 15px; margin: 10px 0; border-radius: 8px; }
        .correct { border-left: 4px solid #22c55e; }
        .wrong   { border-left: 4px solid #ef4444; }
        a { color: #4f46e5; }
    </style>
</head>
<body>
    <div class="score-box">
        <p>Your Score</p>
        <h2>{{ $attempt->score }} / {{ $totalMarks }}</h2>
        <p>Attempt #{{ $attempt->id }}</p>
    </div>

    <h2>Review</h2>
    @foreach($attempt->answers as $answer)
        @php
            $q = $answer->question;
            $correct = $q->options->firstWhere('is_correct', true);
            $isCorrect = $correct && strtolower(trim($correct->option_text)) === strtolower(trim($answer->answer_text));
        @endphp
        <div class="answer-row {{ $isCorrect ? 'correct' : 'wrong' }}">
            <strong>{!! $q->question_text !!}</strong><br>
            Your answer: <em>{{ $answer->answer_text }}</em><br>
            @if(!$isCorrect && $correct)
                Correct answer: <em>{{ $correct->option_text }}</em>
            @endif
        </div>
    @endforeach

    <br>
    <a href="{{ route('quiz.index') }}">← Back to Quizzes</a>
</body>
</html>