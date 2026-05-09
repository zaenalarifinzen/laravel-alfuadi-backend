<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'title',
        'description',
        'content',
        'level',
        'type',
        'options',
        'correct_answer',
        'explanation',
        'display_order',
        'is_active',
        'attempts',
        'passed',
        'metadata',
        'created_by',
    ];

    protected $casts = [
        'options' => 'json',
        'metadata' => 'json',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke User (pembuat soal)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke UserAnswer - satu soal bisa punya banyak jawaban dari berbagai user
     */
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    /**
     * Relasi ke QuestionLevel
     */
    public function questionLevel()
    {
        return $this->belongsTo(QuestionLevel::class, 'level', 'level_number');
    }

    /**
     * Scope: Ambil soal aktif saja
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Ambil soal berdasarkan level
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope: Ambil soal berdasarkan tipe
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
