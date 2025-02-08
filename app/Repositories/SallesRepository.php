<?php

namespace App\Repositories;

use App\Models\Salles;
use App\Repositories\BaseRepository;

class SallesRepository extends BaseRepository
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
        return Salles::class;
    }
}
