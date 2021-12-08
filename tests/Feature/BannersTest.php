<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Banner;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

class BannersTest extends TestCase
{
    use RefreshDatabase;

    public function testCanGetBanners()
    {
        Banner::factory(2)->create();

        $this->getJson('/api/banner')
            ->assertOk()
            ->assertJsonCount(2);
    }

    public function testCancreateBanner()
    {
        $token = Str::random(60);
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make($token),
            'api_token' => hash('sha256', $token),
        ]);

        $response = $this->actingAs($user, 'sanctum')->json('POST','/api/banner',[
            'file'  => UploadedFile::fake()->image('banners/banner.jpg'),
            'title' => UploadedFile::fake()->image('banners/banner.svg'),
        ]);

        $response->assertOk();
        Storage::disk('public')->assertExists('banners/banner.jpg');

        $this->assertDatabaseHas('banners',[
            'url'   => 'banner.jpg',
            'title' => 'banner.svg',
        ]);

        Storage::disk('public')->delete('banners/banner.jpg');
        Storage::disk('public')->delete('banners/banner.svg');
    }

    public function testUnauthorizedUserCanNotCreateBanner()
    {
        $response = $this->json('POST','/api/banner',[
            'file' => UploadedFile::fake()->image('banner.jpg'),
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testCanNotSendFileMimeDifferentFromImage()
    {
        $token = Str::random(60);
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make($token),
            'api_token' => hash('sha256', $token),
        ]);

        $response = $this->actingAs($user, 'sanctum')->json('POST','/api/banner',[
            'file' => UploadedFile::fake()->image('banner.doc'),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testPropsAllRequired()
    {
        $token = Str::random(60);
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make($token),
            'api_token' => hash('sha256', $token),
        ]);

        $response = $this->actingAs($user, 'sanctum')->json('POST','/api/banner');
        
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCanUpdate()
    {
        $token = Str::random(60);
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make($token),
            'api_token' => hash('sha256', $token),
        ]);

        Banner::factory(1)->create([
            'url' => 'icon.jpg',
        ]);

        $response = $this->actingAs($user)->json('POST','/api/banner/1',[
            'file'  => UploadedFile::fake()->image('new_icon.png'),
            'title' => UploadedFile::fake()->image('new_title.svg')
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('banners',[
            'id'  => 1,
            'url' => 'new_icon.png',
        ]);
        Storage::disk('public')->assertExists('banners/new_icon.png');
        Storage::disk('public')->assertExists('banners/new_title.svg');
    }

    public function testGuestCanNotUpdate()
    {
        Banner::factory(1)->create([
            'url'   => 'icon.jpg',
        ]);

        $response = $this->json('POST','/api/banner/1',[
            'file' => UploadedFile::fake()->image('new_icon.png')
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testCanNotUpdateFileMimeDifferentFromImage()
    {
        $token = Str::random(60);
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make($token),
            'api_token' => hash('sha256', $token),
        ]);

        Banner::factory(1)->create([
            'url'   => 'icon.jpg',
        ]);

        $response = $this->actingAs($user, 'sanctum')->json('POST','/api/banner/1',[
            'file'   => UploadedFile::fake()->image('banner.doc'),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCanDeleteBanner()
    {
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make('password'),
        ]);

        Banner::factory(1)->create([
            'url'   => 'icon.jpg',
        ]);

        $this->actingAs($user)->json('DELETE','/api/banner/1')
            ->assertOk();

        $this->assertDatabaseMissing('banners',[
            'id'  => 1,
            'url' => 'icon.jpg'
        ]);
    }

    public function testGuestCanNotDelete()
    {
        Banner::factory(1)->create([
            'url'   => 'icon.jpg',
        ]);

        $this->json('DELETE','/api/banner/1')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
