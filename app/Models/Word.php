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
        'translation_indo',
        'kalimah',
        'jenis',
        'hukum',
        'mabni_detail',
        'kategori',
        'kedudukan',
        'yang_diikuti',
        'irab',
        'tanda',
        'nampak',

        'created_at',
        'updated_at',
        'author',
    ];
}