<?php

namespace App\Repositories\Contracts;

use App\Models\Translation;

interface TranslationRepositoryInterface
{
    public function search(array $filters);
    public function find(int $id): Translation | null;
    public function upsert(array $data): Translation | null;
    public function update(Translation $translation, array $data): Translation | null;
    public function delete(int $id, Translation $translation): bool;
}