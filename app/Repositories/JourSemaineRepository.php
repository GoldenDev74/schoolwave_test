<?php

namespace App\Repositories;

use App\Models\JourSemaine;
use App\Repositories\BaseRepository;

class JourSemaineRepository extends BaseRepository
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
        return JourSemaine::class;
    }
}
