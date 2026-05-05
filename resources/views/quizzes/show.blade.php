<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $quiz->title }}</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .question { border: 1px solid #e5e7eb; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .question img { max-width: 100%; margin: 10px 0; border-radius: 6px; }
        .option label { display: block; margin: 8px 0; cursor: pointer; }
        input[type=text], input[type=number] { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px; }
        button[type=submit] { background: #4f46e5; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; }
        iframe { width: 100%; height: 315px; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>{{ $quiz->title }}</h1>
    <p>{{ $quiz->description }}</p>

    <form method="POST" action="{{ route('quiz.attempt') }}">
        @csrf
        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

        @foreach($quiz->questions as $index => $question)
        <div class="question">
            <h3>Q{{ $index + 1 }}. {!! $question->question_text !!} <small>({{ $question->marks }} mark{{ $question->marks > 1 ? 's' : '' }})</small></h3>

            @if($question->image_path)
                <img src="{{ asset('storage/' . $question->image_path) }}" alt="Question image">
            @endif

            @if($question->video_url)
                @php
                    preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $question->video_url, $matches);
                    $videoId = $matches[1] ?? null;
                @endphp
                @if($videoId)
                    <iframe src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allowfullscreen></iframe>
                @endif
            @endif

            @if(in_array($question->type, ['single_choice', 'binary']))
                @foreach($question->options as $option)
                <div class="option">
                    <label>
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->option_text }}" required>
                        @if($option->image_path)
                            <img src="{{ asset('storage/' . $option->image_path) }}" style="height:60px">
                        @else
                            {{ $option->option_text }}
                        @endif
                    </label>
                </div>
                @endforeach

            @elseif($question->type === 'multiple_choice')
                @foreach($question->options as $option)
                <div class="option">
                    <label>
                        <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option->option_text }}">
                        {{ $option->option_text }}
                    </label>
                </div>
                @endforeach

            @elseif($question->type === 'number')
                <input type="number" name="answers[{{ $question->id }}]" placeholder="Enter a number" required>

            @elseif($question->type === 'text')
                <input type="text" name="answers[{{ $question->id }}]" placeholder="Type your answer" required>
            @endif
        </div>
        @endforeach

        <button type="submit">Submit Quiz</button>
    </form>
</body>
</html>