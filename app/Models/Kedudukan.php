<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
