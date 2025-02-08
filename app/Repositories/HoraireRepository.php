<?php

namespace App\Repositories;

use App\Models\Horaire;
use App\Repositories\BaseRepository;

class HoraireRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'libelle',
        'debut',
        'fin',
        'type_cours'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Horaire::class;
    }
}
