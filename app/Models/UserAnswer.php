<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    use HasFactory;

    protected $table = 'user_answers';

    protected $fillable = [
        'user_id',
        'question_id',
        'level',
        'pass',
        'answer',
        'score',
        'attempt_count',
        'time_spent',
        'is_latest',
        'metadata',
    ];

    protected $casts = [
        'pass' => 'boolean',
        'is_latest' => 'boolean',
        'metadata' => 'json',
        'score' => 'decimal:2',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Question
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Scope: Ambil jawaban terakhir user untuk setiap soal
     */
    public function scopeLatest($query)
    {
        return $query->where('is_latest', true);
    }

    /**
     * Scope: Ambil jawaban user yang sudah pass per level
     */
    public function scopePassedByLevel($query, $userId, $level)
    {
        return $query->where('user_id', $userId)
            ->where('level', $level)
            ->where('pass', true);
    }
}
