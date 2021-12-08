<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Text;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

class TextsTest extends TestCase
{
    use RefreshDatabase;
    
    public function testCanGetTexts()
    {
        Text::factory(2)->create();

        $this->getJson('/api/texts')
            ->assertJsonCount(2);
    }

    public function testCanCreateText()
    {
        $token = Str::random(60);
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make($token),
            'api_token' => hash('sha256', $token),
        ]);

        $response = $this->actingAs($user, 'sanctum')->json('POST','/api/texts',[
            'text'     => 'Lorem Ipsum',
            'image'    => UploadedFile::fake()->image('image.jpg'),
            'doctor'   => 'Doctor pruebas',
            'location' => 'Madrid',
            'title'    => 'titulo'
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('texts',[
            'text'     => 'Lorem Ipsum',
            'url'      => 'image.jpg',
            'doctor'   => 'Doctor pruebas',
            'location' => 'Madrid',
            'title'    => 'titulo'
        ]);

        Storage::disk('public')->assertExists('texts_images/image.jpg');
        Storage::disk('public')->delete('texts_images/image.jpg');
    }

    public function testUnauthorizedUserCanNotCreateText()
    {
        $response = $this->json('POST','/api/texts',[
            'text' => 'Lorem Ipsum',
            'image' => UploadedFile::fake()->image('image.jpg')
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

        $response = $this->actingAs($user, 'sanctum')->json('POST','/api/texts',[
            'text' => 'Lorem Ipsum',
            'image' => UploadedFile::fake()->image('image.doc')
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testPropsAreRequired()
    {
        $token = Str::random(60);
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make($token),
            'api_token' => hash('sha256', $token),
        ]);

        $response = $this->actingAs($user,'sanctum')->json('POST','/api/texts');

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

        Text::factory(1)->create([
            'text' => 'Lorem Ipsum',
            'url'  => 'image.png'
        ]);

        $response = $this->actingAs($user,'sanctum')->json('POST','/api/texts/1',[
            'text' => 'Lorem Ipsum',
            'image' => UploadedFile::fake()->image('image.png')
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('texts',[
            'id'  => 1,
            'url' => 'image.png',
        ]);
        Storage::disk('public')->assertExists('texts_images/image.png');
    }

    public function testGuestCanNotUpdate()
    {
        Text::factory(1)->create([
            'text' => 'Lorem Ipsum',
            'url'  => 'image.png'
        ]);

        $response = $this->json('POST','/api/texts/1',[
            'text' => 'Lorem Ipsum',
            'image' => UploadedFile::fake()->image('image.png')
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

        Text::factory(1)->create([
            'text' => 'Lorem Ipsum',
            'url'  => 'image.png'
        ]);

        $response = $this->actingAs($user)->json('POST','/api/texts/1',[
            'text'  => 'Lorem Ipsum',
            'image' => UploadedFile::fake()->image('banner.doc'),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdateNewTextIsString()
    {
        $token = Str::random(60);
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make($token),
            'api_token' => hash('sha256', $token),
        ]);

        Text::factory(1)->create([
            'text' => 'Lorem Ipsum',
            'url'  => 'image.png'
        ]);

        $response = $this->actingAs($user)->json('POST','/api/texts/1',[
            'text'  => 1,
            'image' => UploadedFile::fake()->image('banner.doc'),
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testTextIsString()
    {
        $token = Str::random(60);
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make($token),
            'api_token' => hash('sha256', $token),
        ]);

        $response = $this->actingAs($user, 'sanctum')->json('POST','/api/texts',[
            'text' => 1,
            'image' => UploadedFile::fake()->image('image.doc')
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdateWithoutImage()
    {
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make('password'),
        ]);

        Text::factory(1)->create([
            'text' => 'Lorem Ipsum',
            'url'  => 'image.png'
        ]);

        $response = $this->actingAs($user)->json('POST','/api/texts/1',[
            'text'  => 'Nuevo Texto',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('texts',[
            'id'   => 1,
            'text' => 'Nuevo Texto',
        ]);
    }

    public function testCanDeleteBanner()
    {
        $user = User::create([
            'name'      => 'Pau Segarra',
            'email'     => 'test@gmail.com',
            'password'  => Hash::make('password'),
        ]);

        Text::factory(1)->create([
            'text' => 'Lorem Ipsum',
            'url'  => 'image.png'
        ]);

        $this->actingAs($user)->json('DELETE','/api/texts/1')
            ->assertOk();

        $this->assertDatabaseMissing('texts',[
            'id'  => 1,
            'text' => 'Lorem Ipsum'
        ]);
    }

    public function testGuestCanNotDelete()
    {
        Text::factory(1)->create([
            'text' => 'Lorem Ipsum',
            'url'  => 'image.png'
        ]);

        $this->json('DELETE','/api/banner/1')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
