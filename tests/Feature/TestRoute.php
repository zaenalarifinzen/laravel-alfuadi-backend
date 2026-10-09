<?php

namespace Tests\Feature;

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
        $this->get('/courses')
            ->assertOk()
            ->assertViewIs('pages.course.index');
    }
}
