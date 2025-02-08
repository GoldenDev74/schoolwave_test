<?php

namespace App\Repositories;

use App\Models\Typepersonnel;
use App\Repositories\BaseRepository;

class TypepersonnelRepository extends BaseRepository
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
        return Typepersonnel::class;
    }
}
