<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class CanLoginTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanLogin()
    {
        User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make('password'),
        ]);

        $this->json('POST','/api/login',[
            'username' => 'test@gmail.com',
            'password' => 'password',
        ])->assertJsonStructure([
            'token'
        ]);
    }

    public function testCredentialsAreRequired()
    {
        $this->json('POST','/api/login')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testWrongCredentialsNotAuth()
    {
        User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make('password'),
        ]);

        $this->json('POST','/api/login',[
            'username' => 'correo@gmail.com',
            'password' => 'contraseña',
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
