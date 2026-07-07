<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Kedudukan extends Model
{
    protected $table = "kedudukan";

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_kalimat',
        'order',
        'simbol',
        'kedudukan_ar',
        'kedudukan_ar_musyakal',
        'kedudukan_in',
        'irob',
    ];

    protected static function booted()
    {
        static::saved(fn() => Cache::forget('data-nahwu'));
        static::deleted(fn() => Cache::forget('data-nahwu'));
        static::restored(fn() => Cache::forget('data-nahwu'));
    }
}
