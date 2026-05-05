<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz System</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .quiz-card { border: 1px solid #ddd; padding: 20px; margin: 15px 0; border-radius: 8px; }
        a.btn { background: #4f46e5; color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Available Quizzes</h1>
    @forelse($quizzes as $quiz)
        <div class="quiz-card">
            <h2>{{ $quiz->title }}</h2>
            <p>{{ $quiz->description }}</p>
            <a class="btn" href="{{ route('quiz.show', $quiz->id) }}">Start Quiz</a>
        </div>
    @empty
        <p>No quizzes available yet.</p>
    @endforelse
</body>
</html>