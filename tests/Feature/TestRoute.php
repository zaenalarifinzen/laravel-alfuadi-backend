<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestRoute extends TestCase
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

    public function testDashboardAsAdmin(): void
    {
        $this->actingAs($this->admin)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('pages.dashboard.dashboard')
            ->assertSeeText('Selamat datang, ' . $this->admin->name);
    }

    public function testDashboardAsUser(): void
    {
        $this->actingAs($this->user)
            ->get('/dashboard')
            ->assertForbidden();
    }

    public function testCourseRouteIndex(): void
    {

        $user = User::factory(1)->create()[0];
        $category = Category::create(['name' => 'Programming']);
        $course = Course::create([
            'mentor_id' => $user->id,
            'category_id' => $category->id,
            'title' => "Course 1",
            'description' => "Description of course 1",
            'price' => 100000,
            'status' => 'published',
        ]);


        $this->get('/courses')
            ->assertOk()
            ->assertViewIs('pages.course.index')
            ->assertSeeText('Course 1');


        // test show route
        $this->get('/courses/' . $course->slug)
            ->assertOk()
            ->assertViewIs('pages.course.show')
            ->assertSeeText('Course 1')
            ->assertSeeText('Description of course 1');
    }
}
