<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCreation(): void
    {
        User::factory()->create([
            'name' => 'Zaenal Arifin',
            'email' => 'zaenal@gmail.com'
        ]);

        $user1 = User::where('email', 'zaenal@gmail.com')->get();

        $this->assertEquals('Zaenal Arifin', $user1[0]->name);
        
        $this->assertDatabaseHas('users', [
            'email' => 'zaenal@gmail.com'
        ]);
    }
}
