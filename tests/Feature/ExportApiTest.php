<?php

namespace Tests\Feature;

use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
class ExportApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a fresh user after DB is refreshed
        $this->user = \App\Models\User::factory()->create();

        // Authenticate the user
        Sanctum::actingAs($this->user, ['*']);
    }

    /** @test */
    public function it_exports_translations_for_a_locale()
    {
        Translation::create(['key' => 'home.welcome', 'locale' => 'en', 'value' => 'Hello']);
        Translation::create(['key' => 'home.welcome', 'locale' => 'fr', 'value' => 'Bonjour']);

        $response = $this->getJson('/api/v1/translations/export?locale=en');

        $response->assertOk()
            ->assertJson(['home' => ['welcome' => 'Hello']])
            ->assertJsonMissing(['welcome' => 'Bonjour']);
    }

    /** @test */
    public function it_exports_flat_json_when_requested()
    {
        Translation::create(['key' => 'auth.login', 'locale' => 'en', 'value' => 'Sign in']);

        $response = $this->getJson('/api/v1/translations/export?locale=en&flat=1');

        $response->assertOk()
            ->assertJson(['auth.login' => 'Sign in']);
    }
}
