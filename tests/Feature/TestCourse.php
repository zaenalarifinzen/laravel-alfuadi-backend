<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestCourse extends TestCase
{
    use RefreshDatabase;

    public function testCreateSuccess(): void
    {
        $user = User::factory(1)->create()[0];
        $category = Category::create(['name' => 'Category 1']);

        Course::create([
            'mentor_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Course 1',
            'description' => 'Description of course 1',
            'price' => 100000,
        ]);

        $courses = Course::all();

        $this->assertNotEmpty($courses);
    }

    public function testCreateDuplicate(): void
    {
        $user = User::factory(1)->create()[0];
        $category = Category::create(['name' => 'Category 1']);

        Course::create([
            'mentor_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Course 1',
            'description' => 'Description of course 1',
            'price' => 100000,
        ]);

        $this->expectException(QueryException::class);
        Course::create([
            'mentor_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Course 1',
            'description' => 'Description of course 1',
            'price' => 100000,
        ]);
    }

    public function testUpdate(): void
    {
        $user = User::factory(1)->create()[0];
        $category = Category::create(['name' => 'Category 1']);

        $course = Course::create([
            'mentor_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Course 1',
            'description' => 'Description of course 1',
            'price' => 100000,
        ]);

        $course->update(['title' => 'Course Updated']);
        $this->assertSame('Course Updated', $course->fresh()->title);
        $this->assertSame('course-updated', $course->fresh()->slug);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => $course->title,
        ]);

        $this->assertDatabaseMissing('courses', [
            'title' => 'Course 1',
        ]);
    }

    public function testDelete(): void
    {
        $user = User::factory(1)->create()[0];
        $category = Category::create(['name' => 'Category 1']);

        $course = Course::create([
            'mentor_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Course 1',
            'description' => 'Description of course 1',
            'price' => 100000,
        ]);
        
        $course->delete();

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    public function testShowController(): void
    {
        $user = User::factory(1)->create()[0];
        $category = Category::create(['name' => 'Category 1']);

        $course = Course::create([
            'mentor_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Course 1',
            'description' => 'Description of course 1',
            'price' => 100000,
        ]);

        $response = $this->actingAs($user)->get(route('mentor.courses.show', ['course' => $course->id]));

        $response->assertStatus(200);
        $response->assertViewIs('mentor.courses.show');
        $response->assertViewHas('course', function ($viewCourse) use ($course) {
            return $viewCourse->id === $course->id;
        });
    }
}
