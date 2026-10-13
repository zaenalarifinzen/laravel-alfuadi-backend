<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_update_does_not_require_status_when_not_sent(): void
    {
        $user = User::factory()->admin()->create();
        $category = Category::create(['name' => 'Backend']);
        $course = Course::create([
            'mentor_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Original title',
            'description' => 'Original description',
            'price' => 100000,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($user)
            ->from(route('dashboard.courses.edit', $course))
            ->put(route('dashboard.courses.update', $course), [
                'title' => 'Updated title',
                'category_id' => $category->id,
                'description' => 'Updated description',
                'price' => '150000',
            ]);

        $response->assertRedirect(route('dashboard.courses.index'))
            ->assertSessionHas('success', 'Kursus berhasil diperbarui.');

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Updated title',
            'category_id' => $category->id,
            'price' => 150000,
        ]);
    }
}
