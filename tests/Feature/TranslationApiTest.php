<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class TranslationApiTest extends TestCase
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
    public function it_creates_a_translation_via_api()
    {
        $payload = [
            'key' => 'auth.login.title',
            'locale' => 'en',
            'value' => 'Sign in',
            'tags' => ['web', 'mobile']
        ];

        $response = $this->postJson('/api/v1/translations', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['value' => 'Sign in']);

        $this->assertDatabaseHas('translations', ['key' => 'auth.login.title', 'locale' => 'en']);
        $this->assertDatabaseHas('tags', ['name' => 'web']);
    }

    /** @test */
    public function it_updates_a_translation_via_api()
    {
        $translation = Translation::create(['key' => 'home.welcome', 'locale' => 'en', 'value' => 'Hello']);

        $response = $this->putJson("/api/v1/translations/{$translation->id}", [
            'value' => 'Welcome!'
        ]);

        $response->assertOk()
            ->assertJsonFragment(['value' => 'Welcome!']);

        $this->assertDatabaseHas('translations', ['id' => $translation->id, 'value' => 'Welcome!']);
    }

    /** @test */
    public function it_searches_translations_by_locale_and_key()
    {
        $tagWeb = Tag::firstOrCreate(['name' => 'web']);

        Translation::create(['key' => 'home.welcome', 'locale' => 'en', 'value' => 'Hello'])->tags()->attach($tagWeb);
        Translation::create(['key' => 'home.welcome', 'locale' => 'fr', 'value' => 'Bonjour'])->tags()->attach($tagWeb);

        $response = $this->getJson('/api/v1/translations?locale=en&key=home');

        $response->assertOk()
            ->assertJsonFragment(['value' => 'Hello'])
            ->assertJsonMissing(['value' => 'Bonjour']);
    }

    /** @test */
    public function it_deletes_a_translation_via_api()
    {
        $translation = Translation::create(['key' => 'auth.logout', 'locale' => 'en', 'value' => 'Logout']);

        $response = $this->deleteJson("/api/v1/translations/{$translation->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('translations', ['id' => $translation->id]);
    }
}
