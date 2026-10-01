<?php

namespace Tests\Feature;

use App\Models\ExerciseLevel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestLevel extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCurrentLevel(): void
    {
        $user = User::factory(1)->create()[0];
        $level1 = ExerciseLevel::create([
            'name' => 'Beginner',
            'slug' => 'beginner',
            'level_number' => 1,
        ]);
        $level2 = ExerciseLevel::create([
            'name' => 'Intermediate',
            'slug' => 'intermediate',
            'level_number' => 2,
        ]);
        $level3 = ExerciseLevel::create([
            'name' => 'Advanced',
            'slug' => 'advanced',
            'level_number' => 3,
        ]);

        $levels = ExerciseLevel::orderBy('level_number', 'asc')->get();
        $currentLevel = $levels->firstWhere('slug', 'intermediate');
        $nextLevel = $levels->firstWhere('level_number', '>', $currentLevel->level_number);

        $this->assertEquals('Intermediate', $currentLevel->name);
        $this->assertEquals('Advanced', $nextLevel->name);
        
    }
}
