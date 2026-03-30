<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
 protected $table = 'kategori';

 protected $primaryKey = 'id';
 public $incrementing = false;
 protected $keyType = 'string'; 

 protected $fillable = [
    'id',
    'id_kalimat',
    'order',
    'simbol',
    'kategori_ar',
    'kategori_ar_musyakal',
    'kategori_in',
    'hukum',
    'rofa',
    'nashob',
    'jar',
    'jazm',
 ];
}
