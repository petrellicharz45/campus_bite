<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InfoPageTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_policy_and_hours_pages_are_accessible(): void
    {
        $this->get(route('pages.privacy'))
            ->assertOk()
            ->assertSee('Privacy Policy')
            ->assertSee('Information we collect');

        $this->get(route('pages.terms'))
            ->assertOk()
            ->assertSee('Terms of Service')
            ->assertSee('Order acceptance and fulfillment');

        $this->get(route('pages.refunds'))
            ->assertOk()
            ->assertSee('Refund and Order Policy')
            ->assertSee('When a refund or replacement may apply');

        $this->get(route('pages.hours'))
            ->assertOk()
            ->assertSee('Operating Hours')
            ->assertSee('Weekly opening schedule');
    }
}
