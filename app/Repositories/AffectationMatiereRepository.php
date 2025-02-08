<?php

namespace App\Repositories;

use App\Models\AffectationMatiere;
use App\Repositories\BaseRepository;

class AffectationMatiereRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'classe',
        'annee_scolaire',
        'matiere',
        'enseignant',
        'horaire',
        'type_cours',
        'jour',
        'mode_affection',
        'debut',
        'fin'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return AffectationMatiere::class;
    }
}
