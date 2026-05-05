<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #f5f4f0; --surface: #ffffff; --surface2: #f0efe9;
            --border: #e2e0d8; --border-strong: #c8c6bc;
            --text: #1a1916; --text-muted: #6b6960; --text-light: #9b9990;
            --accent: #2d5a27; --accent-light: #e8f0e6; --accent-border: #b8d4b3;
            --danger: #c0392b; --danger-light: #fdf0ef; --danger-border: #f5c6c2;
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
        /* Score card */
        .score-card { background: var(--surface); border: 1px solid var(--accent-border); border-radius: var(--radius); padding: 2rem 1.5rem; margin-bottom: 2rem; box-shadow: var(--shadow); text-align: center; }
        .score-label { font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 10px; }
        .score-number { font-size: 48px; font-weight: 600; letter-spacing: -2px; color: var(--accent); line-height: 1; margin-bottom: 6px; }
        .score-sub { font-size: 13px; color: var(--text-muted); }
        /* Section label */
        .section-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: var(--text-light); margin-bottom: 10px; }
        /* Answer review cards */
        .answer-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.25rem 1.5rem; margin-bottom: 10px; box-shadow: var(--shadow); display: grid; grid-template-columns: 18px 1fr; gap: 12px; align-items: start; }
        .answer-card.correct { border-left: 3px solid var(--accent); }
        .answer-card.wrong   { border-left: 3px solid var(--danger); }
        .answer-indicator { width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; margin-top: 2px; flex-shrink: 0; }
        .indicator-correct { background: var(--accent-light); color: var(--accent); border: 1px solid var(--accent-border); }
        .indicator-wrong   { background: var(--danger-light); color: var(--danger); border: 1px solid var(--danger-border); }
        .answer-body {}
        .answer-q { font-size: 14px; font-weight: 500; margin-bottom: 8px; line-height: 1.5; }
        .answer-meta { font-size: 13px; color: var(--text-muted); display: flex; flex-direction: column; gap: 4px; }
        .answer-meta strong { color: var(--text); }
        .tag { display: inline-block; font-size: 11px; font-weight: 500; padding: 2px 8px; border-radius: 99px; margin-right: 4px; }
        .tag-correct { background: var(--accent-light); color: var(--accent); border: 1px solid var(--accent-border); }
        .tag-wrong    { background: var(--danger-light); color: var(--danger); border: 1px solid var(--danger-border); }
        /* Btn row */
        .btn-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 1.5rem; }
        .divider { border: none; border-top: 1px solid var(--border); margin: 1.5rem 0; }
    </style>
</head>
<body>

<header class="header">
    <a href="{{ url('/') }}" class="header-brand">
        <span class="dot"></span>Quiz System
    </a>
    <a href="{{ url('/') }}" class="btn">← All quizzes</a>
</header>

<div class="container">
    <h1 class="page-title">Results</h1>
    <p class="page-subtitle">Attempt #{{ $attempt->id }}</p>

    <div class="score-card">
        <div class="score-label">Your Score</div>
        <div class="score-number">{{ $attempt->score }}<span style="font-size:28px;font-weight:400;color:var(--text-muted)"> / {{ $totalMarks }}</span></div>
        <div class="score-sub">{{ round(($attempt->score / max($totalMarks, 1)) * 100) }}% correct</div>
    </div>

    <div class="section-label">Review</div>

    @foreach($attempt->answers as $answer)
        @php
            $q           = $answer->question;
            $typeHandler = $registry->get($q->type);
            $earned      = $typeHandler->evaluate($q, $answer);
            $isCorrect   = $earned > 0;

            if ($typeHandler->hasOptions()) {
                $correctDisplay = $q->options
                    ->where('is_correct', true)
                    ->pluck('option_text')
                    ->implode(', ');
            } else {
                $correctDisplay = optional($q->options->firstWhere('is_correct', true))->option_text ?? '—';
            }
        @endphp
        <div class="answer-card {{ $isCorrect ? 'correct' : 'wrong' }}">
            <div class="answer-indicator {{ $isCorrect ? 'indicator-correct' : 'indicator-wrong' }}">
                {{ $isCorrect ? '✓' : '✗' }}
            </div>
            <div class="answer-body">
                <div class="answer-q">{!! $q->question_text !!}</div>
                <div class="answer-meta">
                    <div>Your answer: <strong>{{ $answer->answer_text ?: '(no answer)' }}</strong></div>
                    @if(!$isCorrect)
                        <div>Correct answer: <strong>{{ $correctDisplay }}</strong></div>
                    @endif
                    <div style="margin-top:4px">
                        <span class="tag {{ $isCorrect ? 'tag-correct' : 'tag-wrong' }}">
                            {{ $earned }} / {{ $q->marks }} mark{{ $q->marks > 1 ? 's' : '' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <hr class="divider">
    <div class="btn-row">
        <a href="{{ route('quiz.index') }}" class="btn btn-primary">← Back to Quizzes</a>
    </div>
</div>

</body>
</html>