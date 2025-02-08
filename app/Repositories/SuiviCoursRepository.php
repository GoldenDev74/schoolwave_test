<?php

namespace App\Repositories;

use App\Models\SuiviCours;
use App\Repositories\BaseRepository;

class SuiviCoursRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'date',
        'titre',
        'resume',
        'observation',
        'affection_matiere'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return SuiviCours::class;
    }
}
