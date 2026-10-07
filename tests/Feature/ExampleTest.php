<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Guests can view the landing page and reach the login entry point.
     */
    public function test_guests_can_view_the_landing_page(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Diet Soda | Pure Zero Refreshment', false)
            ->assertSee(route('login'), false)
            ->assertSee('Login');
    }

    /**
     * Authenticated users can access the dashboard.
     */
    public function test_authenticated_users_can_view_the_dashboard(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response
            ->assertOk()
            ->assertSee('AI Delay-Risk Prioritization')
            ->assertSee('Total Parcels Today')
            ->assertSee($user->name);
    }
}
