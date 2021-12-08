<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PublicTest extends TestCase
{
    use RefreshDatabase;

    public function testCanGetPublicView()
    {
        $this->get('/')
            ->assertViewHas('banners')
            ->assertViewHas('texts')
            ->assertViewHas('subBanners');
    }
}
