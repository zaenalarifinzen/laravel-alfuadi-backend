<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Surah extends Model
{
    use HasFactory;

    protected $table = 'surahs';

    protected $fillable = [
        'name',
        'name_id',
        'name_en',
        'location',
        'verse_count',
    ];

    public $timestamps = false;

    // get the verses of the surah
    public function verses(): HasMany
    {
        return $this->hasMany(Verse::class, 'surah_id', 'id');
    } 
}
