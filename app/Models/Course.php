<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function title(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value === null
                ? null
                : Str::title(preg_replace('/\s+/', ' ', trim($value))),
        );
    }

    protected static function booted() : void
    {
        static::saving(function ($course) {
            if ($course->isDirty('title')) {
                $course->slug = Str::slug($course->title);
            }
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

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('display_order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function quizzes()
    {
        return $this->hasManyThrough(Quiz::class, Lesson::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isFree() : bool
    {
        return $this->price == 0;
    }
}
