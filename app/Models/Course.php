<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    protected $fillable = [
        'mentor_id',
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'thumbnail',
        'status',
    ];

    protected static function booted() : void
    {
        static::creating(function ($course) {
            $course->slug = Str::slug($course->title);
        });
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function lessons ()
    {
        return $this->hasMany(Lesson::class)->orderBy('display_order');
    }

    public function isFree() : bool
    {
        return $this->price == 0;
    }
}
