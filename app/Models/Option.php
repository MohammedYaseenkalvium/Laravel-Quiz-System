<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

class Option extends Model
{
    protected $fillable = ['question_id', 'option_text', 'is_correct','image_path'];
    
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
