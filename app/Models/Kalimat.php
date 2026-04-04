<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
