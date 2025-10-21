<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordGroups extends Model
{
    use HasFactory;

    protected $table = 'word_groups';

    protected $fillable = [
        'surah_id',
        'verse_number',
        'verse_id',
        'order_number',
        'text',
        'created_at',
        'updated_at',
        'editor',
    ];
}
