<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
        'verse_id',
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

    protected $appends = [
        'display_content',
        'display_correct_answer',
    ];

    public static function findOrCreateAnalysisQuestion($verseId, $level = 1)
    {
        $admin = User::where('roles', 'administrator')->first();
        $adminId = $admin ? $admin->id : 1;

        return self::firstOrCreate(
            [
                'verse_id' => $verseId,
                'type' => 'analysis',
                'level' => $level,
            ],
            [
                'title' => "Analisa ayat {$verseId}",
                'description' => 'Soal analisa dari ayat',
                'content' => null,
                'correct_answer' => null,
                'type' => 'analysis',
                'level' => $level,
                'is_active' => true,
                'created_by' => $adminId,
            ]
        );
    }

    /**
     * Relasi ke Verse
     */
    public function verse() {
        return $this->belongsTo(Verse::class);
    }

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

    public function getDisplayContentAttribute()
    {
        if ($this->type === 'analysis' && $this->verse) {
            return $this->verse->text;
        }

        return $this->content;
    }

    public function getDisplayCorrectAnswerAttribute()
    {
        if ($this->type === 'analysis' && $this->verse) {
            return $this->verse->translation_indo;
        }

        return $this->correct_answer;
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
