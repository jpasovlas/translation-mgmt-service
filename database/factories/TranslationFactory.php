<?php

namespace Database\Factories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    // Pull locales once from config (not env directly)
    protected array $locales;

    public function __construct(...$args)
    {
        parent::__construct(...$args);
        $this->locales = config('translations.locales', ['en','fr','es']);
    }

    public function definition(): array
    {
        return [
            'key'    => $this->faker->unique()->lexify('key_??????'),
            'locale' => $this->faker->randomElement($this->locales),
            'value'  => $this->faker->sentence(4),
            'notes'  => $this->faker->optional()->sentence(),
        ];
    }
}