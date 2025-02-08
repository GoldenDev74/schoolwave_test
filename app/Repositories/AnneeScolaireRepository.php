<?php

namespace App\Repositories;

use App\Models\AnneeScolaire;
use App\Repositories\BaseRepository;

class AnneeScolaireRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'libelle',
        'en_cours'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return AnneeScolaire::class;
    }

    public function updateAll(array $attributes)
    {
        return $this->model->query()->update($attributes);
    }
}
