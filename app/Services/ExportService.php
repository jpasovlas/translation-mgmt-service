<?php

namespace App\Services;

use App\Models\Translation;
use Illuminate\Support\Arr;

class ExportService
{
    /**
     * Export translations to array.
     * 
     * @param string|null $locale
     * @param array|null $tags
     * @param bool $flat
     * @return array
     */
    public function export(?string $locale, ?array $tags, bool $flat = false): array
    {
        $query = Translation::query();

        if ($locale) {
            $query->where('locale', $locale);
        }

        if ($tags) {
            $query->whereHas('tags', fn($q) => $q->whereIn('name', $tags));
        }

        $rows = $query->get(['key','locale','value']);

        $result = [];
        
        foreach ($rows as $row) {
            if ($flat) {
                $result[$row->locale][$row->key] = $row->value;
            } else {
                Arr::set($result[$row->locale], $row->key, $row->value);
            }
        }

        return $locale ? ($result[$locale] ?? []) : $result;
    }
}
