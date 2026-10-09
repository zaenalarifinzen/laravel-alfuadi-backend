<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value === null
                ? null
                : Str::title(preg_replace('/\s+/', ' ', trim($value))),
        );
    }

    protected static function booted(): void
    {
        static::saving(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
