<?php

namespace App\Repositories;

use App\Models\Eleve;
use App\Repositories\BaseRepository;

class EleveRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nom_prenom',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'pays_residence',
        'telephone',
        'email',
        'sexe',
        'parent'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Eleve::class;
    }
}
