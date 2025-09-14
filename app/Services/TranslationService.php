<?php

namespace App\Services;

use App\Repositories\Contracts\TranslationRepositoryInterface;
use App\Models\Translation;

class TranslationService
{
    public function __construct(
        protected TranslationRepositoryInterface $repo
    ) {}

    /**
     * List all translations
     * 
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list(array $filters)
    {
        return $this->repo->search($filters);
    }

    /**
     * Get a translation by id
     * 
     * @param int $id
     * @return Translation|null
     */
    public function get(int $id): Translation | null
    {
        return $this->repo->find($id);
    }

    /**
     * Create or update a translation
     * 
     * @param array $data
     * @return Translation|null
     */
    public function createOrUpdate(array $data): Translation | null
    {
        return $this->repo->upsert($data);
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
        return $this->repo->update($translation, $data);
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
        return $this->repo->delete($id, $translation);
    }
}
