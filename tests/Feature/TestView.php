<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestView extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->user = User::factory()->user()->create();
    }

    public function testCreateCourseView(): void
    {
        Category::create(['name' => 'Coding']);

        $response = $this->actingAs($this->admin)
            ->get('/dashboard/courses/create')
            ->assertOk()
            ->assertViewIs('pages.dashboard.course.create')
            ->assertSeeText('Coding');
    }

    public function testEditCourseView(): void
    {
        $category1 = Category::create(['name' => 'Coding']);
        $category2 = Category::create(['name' => 'UIUX']);
        $course = Course::create([
            'title' => 'Course 1',
            'description' => 'Description of course 1',
            'category_id' => $category1->id,
            'mentor_id' => $this->admin->id,
            'price' => 99000,
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/dashboard/courses/' . $course->id . '/edit')
            ->assertOk()
            ->assertViewIs('pages.dashboard.course.edit')
            ->assertSee('Course 1')
            ->assertSee('selected')
            ->assertSee('99000');
    }
}
