<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $table = 'words';

    protected $fillable = [
        'word_group_id',
        'order_number',
        'text',
        'translation',
        'kalimat',
        'color',
        'kategori',
        'hukum',
        'kedudukan',
        'irob',
        'tanda',
        'simbol',

        'created_at',
        'updated_at',
        'editor',
    ];

    public function wordGroup()
    {
        return $this->belongsTo(WordGroups::class, 'word_group_id', 'id');
    }
}
