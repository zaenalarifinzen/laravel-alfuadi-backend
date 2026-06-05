<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionLevel;

class QuestionLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Pemula',
                'slug' => 'pemula',
                'level_number' => 1,
                'display_order' => 10,
                'description' => 'Level pemula',
                'is_active' => true,
            ],
            [
                'name' => 'Menengah',
                'slug' => 'menengah',
                'level_number' => 2,
                'display_order' => 20,
                'description' => 'Level menengah',
                'is_active' => true,
            ],
            [
                'name' => 'Lanjutan',
                'slug' => 'lanjutan',
                'level_number' => 3,
                'display_order' => 30,
                'description' => 'Level lanjutan',
                'is_active' => true,
            ],
            [
                'name' => 'Al-Quran',
                'slug' => 'alquran',
                'level_number' => 99,
                'display_order' => 99,
                'description' => 'Latihan khusus Al-Quran',
                'is_active' => true,
            ],
        ];

        foreach ($levels as $lvl) {
            QuestionLevel::updateOrCreate(
                ['level_number' => $lvl['level_number']],
                $lvl
            );
        }
    }
}
