<?php

namespace App\Repositories;

use App\Models\Controle;
use App\Repositories\BaseRepository;

class ControleRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'effectif',
        'affectation_cours',
        'date_controle',
        'present'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Controle::class;
    }
}
