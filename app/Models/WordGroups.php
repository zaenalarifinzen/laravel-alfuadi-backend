<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordGroups extends Model
{
    use HasFactory;

    protected $fillable = [
        'surah_id',
        'verse_number',
        'order_number',
        'text',
    ];

}
