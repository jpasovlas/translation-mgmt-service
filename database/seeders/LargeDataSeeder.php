<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Translation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LargeDataSeeder extends Seeder
{
    public function run(): void
    {
        // Pre-create tags once
        $tagNames = config('translations.tags', ['web','mobile','desktop']);
        $tags = [];
        foreach ($tagNames as $tagName) {
            $tags[] = Tag::firstOrCreate(['name' => $tagName]);
        }
        $tagIds = collect($tags)->pluck('id');

        // Insert translations in chunks
        $total = 200000;
        $batchSize = 10000; // insert in chunks of 10k for speed

        for ($i = 0; $i < $total / $batchSize; $i++) {
            $translations = Translation::factory()->count($batchSize)->make();

            // Bulk insert translations
            DB::table('translations')->insert($translations->toArray());

            // Attach random tags in bulk
            $translationIds = Translation::latest('id')->take($batchSize)->pluck('id');
            $pivotData = [];
            foreach ($translationIds as $id) {
                $randomTags = $tagIds->random(rand(1, 2));
                foreach ($randomTags as $tagId) {
                    $pivotData[] = [
                        'translation_id' => $id,
                        'tag_id' => $tagId,
                    ];
                }
            }
            DB::table('tag_translation')->insert($pivotData);

            $this->command->info("Inserted batch " . ($i + 1));
        }
    }
}
