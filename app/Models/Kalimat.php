<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Kalimat extends Model
{
    use HasFactory;

    protected $table = 'kalimat';

    protected $fillable = [
        'id',
        'kalimat_ar',
        'kalimat_ar_musyakal',
        'kalimat_in',
    ];

    protected static function booted()
    {
        static::saved(fn() => Cache::forget('data-nahwu'));
        static::deleted(fn() => Cache::forget('data-nahwu'));
        // static::restored(fn() => Cache::forget('data-nahwu'));
    }
}
