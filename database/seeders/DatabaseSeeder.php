<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\ExerciseLevel;
use App\Models\Product;
use App\Models\Surah;
use App\Models\User;
use App\Models\Verse;
use App\Models\Word;
use App\Models\WordGroup;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Product::factory(10)->create();

        Surah::factory(10)->create()->each(function (Surah $surah) {
            $verseCount = rand(3, 5);

            for ($verseNumber = 1; $verseNumber <= $verseCount; $verseNumber++) {
                $verse = Verse::factory()->create([
                    'surah_id' => $surah->id,
                    'number' => $verseNumber,
                ]);

                $wordgroupCount = rand(2, 4);
                for ($wgOrder = 1; $wgOrder <= $wordgroupCount; $wgOrder++) {
                    $wordGroup = WordGroup::factory()->create([
                        'surah_id' => $surah->id,
                        'verse_id' => $verse->id,
                        'verse_number' => $verse->number,
                        'order_number' => $wgOrder,
                    ]);

                    $wordCount = rand(1, 3);
                    for ($wOrder = 1; $wOrder <= $wordCount; $wOrder++) {
                        Word::factory()->create([
                            'word_group_id' => $wordGroup->id,
                            'order_number' => $wOrder,
                        ]);
                    }
                }
            }
        });

        for ($levelNum = 1; $levelNum <= 3; $levelNum++) {
            $exerciseLevel = ExerciseLevel::factory()->create([
                'name' => "Level $levelNum",
                'slug' => "level$levelNum",
                'level_number' => $levelNum,
                'display_order' => $levelNum,
            ]);

            for ($exNum = 1; $exNum <= 3; $exNum++) {
                    Exercise::factory()->create([
                        'title' => "Exercise $exNum",
                        'level_id' => $exerciseLevel->id,
                        'type' => 'analysis',
                        'display_order' => $exNum,
                    ]);
                }
        }

        $this->call([
            UserSeeder::class,
            ExerciseLevelSeeder::class,
        ]);
    }
}
