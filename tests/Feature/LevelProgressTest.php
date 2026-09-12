<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\ExerciseLevel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LevelProgressTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function testStoreExerciseSubmission(): void
    {
        $user = User::factory(1)->create()[0];
        $level = ExerciseLevel::create([
            'name' => 'Beginner',
            'slug' => 'beginner',
            'level_number' => 1,
        ]);

        $exercise1 = Exercise::create([
            'title' => 'Exercise 1',
            'type' => 'analysis',
            'level_id' => $level->id,
            'display_order' => 1,
            'is_active' => true,
            'attempt' => 0,
            'passed' => 0,
            'created_by' => $user->id,
        ]);
        $exercise2 = Exercise::create([
            'title' => 'Exercise 2',
            'type' => 'analysis',
            'level_id' => $level->id,
            'display_order' => 2,
            'is_active' => true,
            'attempt' => 0,
            'passed' => 0,
            'created_by' => 1,
        ]);


        $isNotLastInLevel = !Exercise::active()->where('level_id', $exercise1->level_id)
            ->where('display_order', '>', $exercise1->display_order)
            ->exists();

        $this->assertFalse($isNotLastInLevel);

        $isLastInLevel = !Exercise::active()->where('level_id', $exercise2->level_id)
            ->where('display_order', '>', $exercise2->display_order)
            ->exists();

        $this->assertTrue($isLastInLevel);
    }

    public function testGetUserLevelProgress ()
    {
        $user = User::factory(1)->create()[0];

        
    }
    
}
