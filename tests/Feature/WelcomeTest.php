<?php

namespace Tests\Feature;

use Tests\TestCase;

class WelcomeTest extends TestCase
{
    /** @test */
    public function itCanGetWelcomeView()
    {
        $response = $this->get(route('welcome'));
        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }
}
