<?php

namespace App\Repositories;

use App\Models\TypeCours;
use App\Repositories\BaseRepository;

class TypeCoursRepository extends BaseRepository
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
        return TypeCours::class;
    }
}
