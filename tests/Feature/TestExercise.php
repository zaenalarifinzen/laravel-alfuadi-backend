<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\ExerciseLevel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestExercise extends TestCase
{
    use RefreshDatabase;

    public function testIndex(): void
    {
        $user = User::factory()->create();
        $level = ExerciseLevel::create([
            'name' => 'Test Level',
            'slug' => 'test-level',
            'level_number' => 1,
            'display_order' => 1,
            'description' => 'Test level',
            'is_active' => true,
        ]);

        $createdExercise = Exercise::factory()->create([
            'level_id' => $level->level_number,
            'created_by' => $user->id,
        ]);

        $exercises = Exercise::with('exerciseLevel')
            ->orderBy('level_id', 'asc')
            ->orderBy('display_order', 'asc')
            ->get();

        $exercise = $exercises->firstWhere('id', $createdExercise->id);

        $this->assertNotNull($exercise);
        $this->assertTrue($exercise->relationLoaded('exerciseLevel'));
        $this->assertSame($level->id, $exercise->exerciseLevel->id);
    }
}
