<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verse extends Model
{
    use HasFactory;

    protected $table = 'verses';

    protected $fillable = [
        'surah_id',
        'number',
        'text',
        'translation_indo',
    ];

    public $timestamps = false;

    /**
     * Relasi ke Surah
     */
    public function surah()
    {
        return $this->belongsTo(Surah::Class, 'surah_id');
    }

    /**
     * Relasi ke WordGroups - ambil semua grup kata untuk ayat ini
     */
    public function wordGroups()
    {
        return $this->hasMany(WordGroups::class, 'verse_id', 'id');
    }
}
