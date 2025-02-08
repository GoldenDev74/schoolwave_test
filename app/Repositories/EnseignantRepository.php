<?php

namespace App\Repositories;

use App\Models\Enseignant;
use App\Repositories\BaseRepository;

class EnseignantRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nom_prenom',
        'date_naissance',
        'date_engagement',
        'date_diplome',
        'diplome',
        'filiere',
        'sexe',
        'type_cours'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Enseignant::class;
    }
}
