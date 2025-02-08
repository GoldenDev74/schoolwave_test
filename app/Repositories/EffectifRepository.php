<?php

namespace App\Repositories;

use App\Models\Effectif;
use App\Repositories\BaseRepository;

class EffectifRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'annee_scolaire',
        'classe',
        'eleve'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Effectif::class;
    }
}
