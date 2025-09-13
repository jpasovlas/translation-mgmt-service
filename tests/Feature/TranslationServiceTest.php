<?php

namespace Tests\Unit;

use App\Models\Translation;
use App\Repositories\Contracts\TranslationRepositoryInterface;
use App\Services\TranslationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class TranslationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $repo;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = Mockery::mock(TranslationRepositoryInterface::class);
        $this->service = new TranslationService($this->repo);
    }

    /** @test */
    public function it_lists_translations_with_filters()
    {
        $filters = ['locale' => 'en'];
        $expected = collect([new Translation(['key' => 'home.welcome', 'locale' => 'en', 'value' => 'Hello'])]);

        $this->repo->shouldReceive('search')->once()->with($filters)->andReturn($expected);

        $result = $this->service->list($filters);

        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function it_creates_or_updates_a_translation()
    {
        $data = ['key' => 'home.welcome', 'locale' => 'en', 'value' => 'Hello'];
        $translation = new Translation($data);

        $this->repo->shouldReceive('upsert')->once()->with($data)->andReturn($translation);

        $result = $this->service->createOrUpdate($data);

        $this->assertInstanceOf(Translation::class, $result);
        $this->assertEquals('Hello', $result->value);
    }
}
