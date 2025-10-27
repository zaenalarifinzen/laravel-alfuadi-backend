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
}
