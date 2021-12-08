<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class CanLogoutTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanLogout()
    {
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make('password'),
        ]);

        $this->actingAs($user,'sanctum')
            ->json('POST','/api/logout')
            ->assertOk();
    }

    public function testNotLoggedUserCanNotLogout()
    {
        $this->json('POST','/api/logout')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
