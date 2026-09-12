<?php

namespace Database\Seeders;

use App\Models\ExerciseLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExerciseLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExerciseLevel::create([
            'id' => 99,
            'name' => 'Al-Quran',
            'slug' => 'alquran',
            'level_number' => 99,
            'display_order' => 99,
            'description' => 'Soal ayat-ayat Al-Quran',
            'is_active' => true,
            'metadata' => null,
        ]);
    }
}
