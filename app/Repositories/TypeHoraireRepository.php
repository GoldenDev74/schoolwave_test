<?php

namespace App\Repositories;

use App\Models\TypeHoraire;
use App\Repositories\BaseRepository;

class TypeHoraireRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'libelle'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TypeHoraire::class;
    }
}
