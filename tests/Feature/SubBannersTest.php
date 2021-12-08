<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SubBanner;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class SubBannersTest extends TestCase
{
    use RefreshDatabase;
    
    public function testCanGetSubBanners()
    {
        SubBanner::factory(2)->create();

        $this->getJson('/api/subbanner')
            ->assertOk()
            ->assertJsonCount(2);
    }

    public function testCanCreateSubBanners()
    {
        $token = Str::random(60);
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make($token),
            'api_token' => hash('sha256', $token),
        ]);

        $response = $this->actingAs($user, 'sanctum')->json('POST','/api/subbanner',[
            'icon'   => UploadedFile::fake()->image('sub-banner.jpg'),
            'title'  => 'Sub Banner 1',
            'period' => '2021-12-17'
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('sub_banners',[
            'icon'   => 'sub-banner.jpg',
            'title'  => 'Sub Banner 1',
            'period' => '2021-12-17',
        ]);

        Storage::disk('public')->assertExists('sub-banners/sub-banner.jpg');
        Storage::disk('public')->delete('sub-banners/sub-banner.jpg');
    }

    public function testUnauthorizedUserCanNotCreateSubBanners()
    {

        $response = $this->json('POST','/api/subbanner',[
            'icon'   => UploadedFile::fake()->image('sub-banner.jpg'),
            'title'  => 'Sub Banner 1',
            'period' => '2021-12-17'
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

        $response = $this->actingAs($user, 'sanctum')->json('POST','/api/subbanner',[
            'icon'   => UploadedFile::fake()->image('sub-banner.doc'),
            'title'  => 'Sub Banner 1',
            'period' => '2021-12-17'
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

        $response = $this->actingAs($user, 'sanctum')->json('POST','/api/subbanner');
        
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

        SubBanner::factory(1)->create([
            'icon'   => 'icon.jpg',
            'title'  => 'Icono',
            'period' => '2021-12-04',
        ]);

        $response = $this->actingAs($user,'sanctum')->json('POST','/api/subbanner/1',[
            'icon' => UploadedFile::fake()->image('new_icon.png')
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('sub_banners',[
            'id'   => 1,
            'icon' => 'new_icon.png',
        ]);
        Storage::disk('public')->assertExists('sub-banners/new_icon.png');
    }

    public function testGuestCanNotUpdate()
    {
        SubBanner::factory(1)->create([
            'icon'   => 'icon.jpg',
            'title'  => 'Icono',
            'period' => '2021-12-04',
        ]);

        $response = $this->json('POST','/api/subbanner/1',[
            'icon' => UploadedFile::fake()->image('new_icon.png')
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

        SubBanner::factory(1)->create([
            'icon'   => 'icon.jpg',
            'title'  => 'Icono',
            'period' => '2021-12-04',
        ]);

        $response = $this->actingAs($user, 'sanctum')->json('POST','/api/subbanner/1',[
            'icon'   => UploadedFile::fake()->image('sub-banner.doc'),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCanDeleteSubBanner()
    {
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make('password'),
        ]);

        SubBanner::factory(1)->create([
            'icon'   => 'icon.jpg',
            'title'  => 'Icono',
            'period' => '2021-12-04',
        ]);

        $this->actingAs($user)->json('DELETE','/api/subbanner/1')
            ->assertOk();

        $this->assertDatabaseMissing('sub_banners',[
            'id'   => 1,
            'icon' => 'icon.png',
        ]);
    }

    public function testGuestCanNotDelete()
    {
        SubBanner::factory(1)->create([
            'icon'   => 'icon.jpg',
            'title'  => 'Icono',
            'period' => '2021-12-04',
        ]);

        $this->json('DELETE','/api/subbanner/1')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testUpdateWithoutIcon()
    {
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make('password'),
        ]);

        SubBanner::factory(1)->create([
            'icon'   => 'icon.jpg',
            'title'  => 'Icono',
            'period' => '2021-12-04',
        ]);

        $response = $this->actingAs($user,'sanctum')->json('POST','/api/subbanner/1',[
            'title'  => 'Nuevo icono',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('sub_banners',[
            'id'   => 1,
            'title' => 'Nuevo icono',
        ]);
    }
}
