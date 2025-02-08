<?php

namespace App\Repositories;

use App\Models\Parents;
use App\Repositories\BaseRepository;

class ParentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nom_prenom',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'adresse',
        'ville',
        'pays_residence',
        'telephone',
        'email',
        'lien_eleve'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Parents::class;
    }
}
