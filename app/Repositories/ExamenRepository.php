<?php

namespace App\Repositories;

use App\Models\Examen;
use App\Repositories\BaseRepository;

class ExamenRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'libelle',
        'type_examen',
        'note',
        'eleve',
        'affectation'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Examen::class;
    }
}
