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
        $this->call([
            UserSeeder::class,
            ExerciseLevelSeeder::class,
        ]);
        
        User::factory(10)->create();
        Surah::factory(14)->create();
        Verse::factory(100)->create();
        WordGroup::factory(100)->create();
        Word::factory(100)->create();
        ExerciseLevel::factory(3)->create();
        Exercise::factory(10)->create();
        
        Product::factory(10)->create();
    }
}
