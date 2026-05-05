<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Attempt;
use App\Models\Question;

class Answer extends Model
{
    protected $fillable = ['attempt_id', 'question_id', 'answer_text'];
    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
