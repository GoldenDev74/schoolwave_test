<?php

namespace App\Repositories;

use App\Models\Classe;
use App\Repositories\BaseRepository;

class ClasseRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'libelle',
        'type_cours',
        'salle'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Classe::class;
    }
}
