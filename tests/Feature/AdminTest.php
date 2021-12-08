<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminRootRedirectsToLogin()
    {
        $this->get('/admin')
            ->assertRedirect('/admin/login');
    }

    public function testAdminCanGetBannersListView()
    {
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make('password'),
        ]);
        $this->actingAs($user)
            ->get('/admin/banners')
            ->assertViewIs('banners.index');
    }

    public function testGuestCanNotGetBannersListView()
    {
        $this->get('/admin/banners')
            ->assertRedirect('/admin/login');
    }

    public function testAdminCanGetSubBannersListView()
    {
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make('password'),
        ]);
        $this->actingAs($user)
            ->get('/admin/sub-banners')
            ->assertViewIs('sub-banners.index');
    }

    public function testGuestCanNotGetSubBannersListView()
    {
        $this->get('/admin/sub-banners')
            ->assertRedirect('/admin/login');
    }

    public function testAdminCanGetTextListView()
    {
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make('password'),
        ]);
        $this->actingAs($user)
            ->get('/admin/text')
            ->assertViewIs('texts.index');
    }

    public function testGuestCanNotGettextListView()
    {
        $this->get('/admin/text')
            ->assertRedirect('/admin/login');
    }
}
