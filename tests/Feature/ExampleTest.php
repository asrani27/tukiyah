<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthenticated access redirects to login.
     */
    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    /**
     * Test login page loads successfully and displays centered logo.
     */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('logo/tala.png');
        $response->assertSee('SIPEKAN BATI-BATI');
        $response->assertSee('flex flex-col items-center justify-center text-center');
    }

    /**
     * Test authenticated user can access dashboard and modules.
     */
    public function test_authenticated_user_can_access_dashboard_and_modules(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/')->assertStatus(200);
        $this->actingAs($user)->get('/desa')->assertStatus(200);
        $this->actingAs($user)->get('/penduduk')->assertStatus(200);
        $this->actingAs($user)->get('/mutasi/pindah')->assertStatus(200);
        $this->actingAs($user)->get('/mutasi/meninggal')->assertStatus(200);
        $this->actingAs($user)->get('/mutasi/lahir')->assertStatus(200);
        $this->actingAs($user)->get('/mutasi/pendatang')->assertStatus(200);
        $this->actingAs($user)->get('/laporan')->assertStatus(200);
        $this->actingAs($user)->get('/laporan/cetak-pdf')->assertStatus(200);
    }
}
