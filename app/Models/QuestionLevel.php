<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionLevel extends Model
{
    use HasFactory;

    protected $table = 'question_levels';

    protected $fillable = [
        'name',
        'level_number',
        'description',
        'color',
        'icon',
        'min_score',
        'question_count',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Question - satu level bisa punya banyak soal
     */
    public function questions()
    {
        return $this->hasMany(Question::class, 'level', 'level_number');
    }

    /**
     * Scope: Ambil level yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
