<?php

namespace App\Repositories;

use App\Models\TypeExamen;
use App\Repositories\BaseRepository;

class TypeExamenRepository extends BaseRepository
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
        return TypeExamen::class;
    }
}
