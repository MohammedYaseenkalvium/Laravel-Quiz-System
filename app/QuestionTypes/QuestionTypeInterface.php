<?php

namespace App\QuestionTypes;

use App\Models\Question;
use App\Models\Answer;

interface QuestionTypeInterface
{
    /**
     * Unique string key, e.g. 'single_choice'
     */
    public function key(): string;

    /**
     * Human-readable label shown in the UI
     */
    public function label(): string;

    /**
     * Whether this type uses the options table for choices
     */
    public function hasOptions(): bool;

    /**
     * Persist options/correct-answer when a quiz is created.
     * $data is the raw question array from the request.
     */
    public function saveOptions(Question $question, array $data): void;

    /**
     * Score a single answer. Returns marks earned (0 or question->marks).
     */
    public function evaluate(Question $question, Answer $answer): int;

    /**
     * Input type hint for the frontend ('radio','checkbox','number','text')
     */
    public function inputType(): string;
}