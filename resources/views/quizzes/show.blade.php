<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quiz->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #f5f4f0; --surface: #ffffff; --surface2: #f0efe9;
            --border: #e2e0d8; --border-strong: #c8c6bc;
            --text: #1a1916; --text-muted: #6b6960; --text-light: #9b9990;
            --accent: #2d5a27; --accent-light: #e8f0e6; --accent-border: #b8d4b3;
            --radius: 10px; --radius-sm: 6px;
            --shadow: 0 1px 3px rgba(0,0,0,0.06);
            --info: #1a4a7a; --info-light: #e8f0f8; --info-border: #b3cce0;
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
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; font-size: 15px; padding: 11px 24px; }
        .btn-primary:hover { background: #234d1e; border-color: #234d1e; }
        /* Question card */
        .question-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.25rem 1.5rem; margin-bottom: 10px; box-shadow: var(--shadow); }
        .q-header { display: flex; align-items: center; gap: 10px; margin-bottom: 1rem; }
        .q-num { width: 28px; height: 28px; border-radius: 50%; background: var(--surface2); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; color: var(--text-muted); flex-shrink: 0; }
        .q-marks { font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 99px; background: var(--info-light); color: var(--info); border: 1px solid var(--info-border); }
        .q-text { font-size: 15px; font-weight: 500; line-height: 1.5; margin-bottom: 1rem; }
        /* Options */
        .options-list { display: flex; flex-direction: column; gap: 8px; }
        .option-label { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-sm); cursor: pointer; font-size: 14px; transition: all .15s; background: var(--surface); }
        .option-label:hover { border-color: var(--accent); background: var(--accent-light); }
        .option-label input { accent-color: var(--accent); width: 15px; height: 15px; flex-shrink: 0; cursor: pointer; }
        /* Free input */
        input[type=text], input[type=number] { width: 100%; max-width: 360px; padding: 9px 12px; font-size: 14px; font-family: 'DM Sans', sans-serif; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); outline: none; transition: border-color .15s, box-shadow .15s; }
        input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(45,90,39,0.1); }
        /* Video embed */
        .video-wrap { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: var(--radius-sm); margin-bottom: 1rem; background: var(--surface2); }
        .video-wrap iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; }
        /* Question image */
        .q-img { max-width: 100%; border-radius: var(--radius-sm); margin-bottom: 1rem; display: block; }
        /* Submit row */
        .submit-row { margin-top: 1.5rem; }
        .hint { font-size: 11px; color: var(--text-light); margin-top: 5px; }
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
    <h1 class="page-title">{{ $quiz->title }}</h1>
    @if($quiz->description)
        <p class="page-subtitle">{{ $quiz->description }}</p>
    @endif

    <form method="POST" action="{{ route('quiz.attempt') }}">
        @csrf
        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

        @foreach($quiz->questions as $index => $question)
            @php $typeHandler = $registry->get($question->type); @endphp
            <div class="question-card">
                <div class="q-header">
                    <div class="q-num">{{ $index + 1 }}</div>
                    <span class="q-marks">{{ $question->marks }} mark{{ $question->marks > 1 ? 's' : '' }}</span>
                </div>

                <div class="q-text">{!! $question->question_text !!}</div>

                @if($question->image_path)
                    <img class="q-img" src="{{ asset('storage/' . $question->image_path) }}" alt="Question image">
                @endif

                @if($question->video_url)
                    @php
                        preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $question->video_url, $matches);
                        $videoId = $matches[1] ?? null;
                    @endphp
                    @if($videoId)
                        <div class="video-wrap">
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" allowfullscreen></iframe>
                        </div>
                    @endif
                @endif

                @if($typeHandler->hasOptions())
                    <div class="options-list">
                        @foreach($question->options as $option)
                            <label class="option-label">
                                <input
                                    type="{{ $typeHandler->inputType() }}"
                                    name="answers[{{ $question->id }}]{{ $typeHandler->inputType() === 'checkbox' ? '[]' : '' }}"
                                    value="{{ $option->option_text }}"
                                    {{ $typeHandler->inputType() === 'radio' ? 'required' : '' }}
                                >
                                @if($option->image_path)
                                    <img src="{{ asset('storage/' . $option->image_path) }}" style="height:52px; border-radius:4px;">
                                @else
                                    {{ $option->option_text }}
                                @endif
                            </label>
                        @endforeach
                    </div>
                @else
                    <input
                        type="{{ $typeHandler->inputType() }}"
                        name="answers[{{ $question->id }}]"
                        placeholder="{{ $typeHandler->inputType() === 'number' ? 'Enter a number' : 'Type your answer' }}"
                        required
                    >
                    <p class="hint">Answer is compared case-insensitively</p>
                @endif
            </div>
        @endforeach

        <div class="submit-row">
            <button type="submit" class="btn btn-primary">Submit Quiz →</button>
        </div>
    </form>
</div>

</body>
</html>