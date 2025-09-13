<?php

namespace App\Repositories\Eloquent;

use App\Models\Translation;
use App\Models\Tag;
use App\Repositories\Contracts\TranslationRepositoryInterface;

class TranslationRepository implements TranslationRepositoryInterface
{
    /**
     * Search translations based on filters
     * 
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(array $filters)
    {
        $conditions = [];
        $q = Translation::select(
                'translations.id',
                'translations.key',
                'translations.locale', 
                'translations.value',
                'translations.notes',
                'tags.name as tag'
            )->join('tag_translation', 'translations.id', '=', 'tag_translation.translation_id')
            ->join('tags', 'tag_translation.tag_id', '=', 'tags.id');

        if (!empty($filters['locale'])) {
            $q->where('translations.locale', $filters['locale']);
        }

        if (!empty($filters['key'])) {
            $q->where('translations.key', 'like', "%{$filters['key']}%");
        }

        if (!empty($filters['content'])) {
            $q->where('translations.value', 'like', "%{$filters['content']}%");
        }

        if (!empty($filters['tags'])) {
            $q->whereIn('tags.name', $filters['tags']);
        }

        return $q->get();
    }

    /**
     * Find a translation by id
     * 
     * @param int $id
     * @return Translation|null
     */
    public function find(int $id): Translation | null
    {
        return Translation::with('tags')->find($id);
    }

    /**
     * Create or update a translation
     * 
     * @param array $data
     * @return Translation|null
     */
    public function upsert(array $data): Translation | null
    {
        $translation = Translation::updateOrCreate(
            ['key' => $data['key'], 'locale' => $data['locale']],
            ['value' => $data['value'] ?? null, 'notes' => $data['notes'] ?? null]
        );

        if (!empty($data['tags'])) {
            $this->syncTags($translation, $data['tags']);
        }

        return $translation->load('tags') ?? null;
    }

    /**
     * Update a translation
     * 
     * @param Translation $translation
     * @param array $data
     * @return Translation|null
     */
    public function update(Translation $translation, array $data): Translation | null
    {
        $translation = $translation->find($data['id']);
        
        if ($translation) {
            $colums = [
                'key' => $translation->key,
                'locale' => $data['locale'] ?? $translation->locale,
                'value' => $data['value'] ?? $translation->value,
                'notes' => $data['notes'] ?? $translation->notes,
            ];

            $translation->fill(array_filter($colums));
            $translation->save();

            if (!empty($data['tags'])) {
                $this->syncTags($translation, $data['tags']);
            }

            return $translation->load('tags');
        }

        return $translation;
    }

    /**
     * Delete a translation
     * 
     * @param int $id
     * @param Translation $translation
     * @return bool
     */
    public function delete(int $id, Translation $translation): bool
    {
        $translation = $translation->find($id);

        if ($translation) {
            $translation->delete();

            return true;
        }

        return false;
    }

    /**
     * Sync tags for a translation
     * 
     * @param Translation $translation
     * @param array $tagNames
     * @return void
     */
    private function syncTags(Translation $translation, array $tagNames): void
    {
        $tagIds = collect($tagNames)->map(fn($name) => Tag::firstOrCreate(['name' => trim($name)])->id);
        $translation->tags()->sync($tagIds);
    }
}