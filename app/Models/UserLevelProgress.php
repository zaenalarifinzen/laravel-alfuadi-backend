<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLevelProgress extends Model
{
    use HasFactory;

    protected $table = 'user_level_progress';

    protected $fillable = [
        'user_id',
        'exercise_level_id',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * Relation to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation to ExerciseLevel
     */
    public function exerciseLevel()
    {
        return $this->belongsTo(ExerciseLevel::class, 'exercise_level_id');
    }
}
