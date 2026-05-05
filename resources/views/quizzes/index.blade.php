<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz System</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #f5f4f0; --surface: #ffffff; --surface2: #f0efe9;
            --border: #e2e0d8; --border-strong: #c8c6bc;
            --text: #1a1916; --text-muted: #6b6960; --text-light: #9b9990;
            --accent: #2d5a27; --accent-light: #e8f0e6; --accent-border: #b8d4b3;
            --danger: #c0392b; --danger-light: #fdf0ef;
            --radius: 10px; --radius-sm: 6px;
            --shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }
        .header { background: var(--surface); border-bottom: 1px solid var(--border); padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; height: 60px; position: sticky; top: 0; z-index: 100; }
        .header-brand { display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 600; color: var(--text); text-decoration: none; }
        .header-brand .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); }
        .container { max-width: 760px; margin: 0 auto; padding: 2.5rem 1.5rem 4rem; }
        .page-title { font-size: 28px; font-weight: 600; letter-spacing: -0.5px; margin-bottom: 4px; }
        .page-subtitle { font-size: 14px; color: var(--text-muted); margin-bottom: 2rem; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; font-size: 14px; font-weight: 500; font-family: 'DM Sans', sans-serif; border-radius: var(--radius-sm); border: 1px solid var(--border-strong); background: var(--surface); color: var(--text); cursor: pointer; transition: all .15s; text-decoration: none; }
        .btn:hover { background: var(--surface2); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover { background: #234d1e; border-color: #234d1e; }
        .quiz-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.25rem 1.5rem; margin-bottom: 10px; box-shadow: var(--shadow); display: flex; align-items: center; justify-content: space-between; gap: 1rem; transition: border-color .15s; }
        .quiz-card:hover { border-color: var(--border-strong); }
        .quiz-card-info h2 { font-size: 16px; font-weight: 600; margin-bottom: 3px; }
        .quiz-card-info p { font-size: 13px; color: var(--text-muted); }
        .empty-state { text-align: center; padding: 3rem 1rem; color: var(--text-light); font-size: 14px; }
        .empty-state .icon { font-size: 32px; margin-bottom: 10px; }
    </style>
</head>
<body>

<header class="header">
    <a href="{{ url('/') }}" class="header-brand">
        <span class="dot"></span>Quiz System
    </a>
    <a href="{{ route('quiz.create') }}" class="btn btn-primary">+ Create Quiz</a>
</header>

<div class="container">
    <h1 class="page-title">Available Quizzes</h1>
    <p class="page-subtitle">Pick a quiz below to get started.</p>

    @forelse($quizzes as $quiz)
        <div class="quiz-card">
            <div class="quiz-card-info">
                <h2>{{ $quiz->title }}</h2>
                @if($quiz->description)
                    <p>{{ $quiz->description }}</p>
                @endif
            </div>
            <a class="btn btn-primary" href="{{ route('quiz.show', $quiz->id) }}" style="white-space:nowrap">Start Quiz →</a>
        </div>
    @empty
        <div class="empty-state">
            <div class="icon">📋</div>
            No quizzes available yet. Create the first one!
        </div>
    @endforelse
</div>

</body>
</html>