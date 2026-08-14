<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class Exercise extends Model
{
    use HasFactory;

    protected $table = 'exercises';

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
        'content' => 'json',
        'options' => 'json',
        'metadata' => 'json',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'display_content',
        'display_correct_answer',
    ];

    public static function findOrCreateQuranExercise($exerciseOrderNumber, $levelNumber = 1)
    {
        $admin = User::where('roles', 'administrator')->first();
        $adminId = $admin ? $admin->id : 1;

        $verse = Verse::find($exerciseOrderNumber);
        $surah = $verse->surah;

        return self::firstOrCreate(
            [
                'verse_id' => $exerciseOrderNumber,
                'type' => 'analysis',
                'level' => $levelNumber,
            ],
            [
                'title' => "Surat {$surah->name}" . " ayat {$verse->number}",
                'description' => $verse->text,
                'content' => null,
                'correct_answer' => null,
                'type' => 'analysis',
                'level' => $levelNumber,
                'is_active' => true,
                'display_order' => $verse->id,
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
     * Relasi ke Exercise Level
     */
    public function exerciseLevel()
    {
        return $this->belongsTo(ExerciseLevel::class, 'level', 'level_number');
    }

    public function getDisplayContentAttribute()
    {
        if ($this->type === 'analysis' && $this->verse) {
            return $this->verse->text;
        }

        return $this->description;
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
