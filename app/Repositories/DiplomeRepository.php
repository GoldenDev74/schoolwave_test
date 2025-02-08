<?php

namespace App\Repositories;

use App\Models\Diplome;
use App\Repositories\BaseRepository;

class DiplomeRepository extends BaseRepository
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
        return Diplome::class;
    }
}
