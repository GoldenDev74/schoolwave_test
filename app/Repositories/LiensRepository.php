<?php

namespace App\Repositories;

use App\Models\Liens;
use App\Repositories\BaseRepository;

class LiensRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'libelle',
        'route'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Liens::class;
    }
}
