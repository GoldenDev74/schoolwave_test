<?php

namespace App\Repositories;

use App\Models\Filere;
use App\Repositories\BaseRepository;

class FilereRepository extends BaseRepository
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
        return Filere::class;
    }
}
