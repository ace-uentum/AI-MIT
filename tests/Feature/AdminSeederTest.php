<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The database seeder provisions the configured admin account.
     */
    public function test_database_seeder_creates_the_configured_admin_account(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', [
            'email' => config('app.admin.email'),
            'is_admin' => true,
        ]);
    }
}
