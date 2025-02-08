<?php

namespace App\Repositories;

use App\Models\Sexe;
use App\Repositories\BaseRepository;

class SexeRepository extends BaseRepository
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
        return Sexe::class;
    }
}
