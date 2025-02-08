<?php

namespace App\Repositories;

use App\Models\Matiere;
use App\Repositories\BaseRepository;

class MatiereRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'libelle',
        'categorie_matiere'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Matiere::class;
    }
}
