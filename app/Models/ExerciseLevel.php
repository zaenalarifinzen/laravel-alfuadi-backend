<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseLevel extends Model
{
    use HasFactory;

    protected $table = 'exercise_levels';

    protected $fillable = [
        'name',
        'slug',
        'level_number',
        'display_order',
        'description',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Exercise - satu level bisa punya banyak soal
     */
    public function exercises()
    {
        return $this->hasMany(Exercise::class, 'level', 'level_number');
    }

    /**
     * Scope: Ambil level yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
