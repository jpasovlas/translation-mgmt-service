<?php

namespace Tests\Unit;

use App\Models\Translation;
use App\Services\ExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_exports_nested_json_by_locale()
    {
        Translation::create(['key' => 'home.welcome', 'locale' => 'en', 'value' => 'Hello']);

        $service = new ExportService();
        $result = $service->export('en', null, false);

        $this->assertEquals(['home' => ['welcome' => 'Hello']], $result);
    }

    /** @test */
    public function it_exports_flat_json()
    {
        Translation::create(['key' => 'auth.login', 'locale' => 'en', 'value' => 'Sign in']);

        $service = new ExportService();
        $result = $service->export('en', null, true);

        $this->assertEquals(['auth.login' => 'Sign in'], $result);
    }
}
