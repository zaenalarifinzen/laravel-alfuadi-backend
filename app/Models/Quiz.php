<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'lesson_id',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    // public function questions()
    // {
    //     return $this->hasMany(Question::class);
    // }
}
