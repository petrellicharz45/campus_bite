<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Campus Bites and Canteen')
            ->assertSee('Featured picks')
            ->assertSee('Payment methods');
    }

    public function test_login_page_does_not_show_demo_credentials(): void
    {
        $response = $this->get('/login');

        $response
            ->assertOk()
            ->assertDontSee('Demo admin')
            ->assertDontSee('Demo student')
            ->assertDontSee('student@campusbites.test');
    }
}
