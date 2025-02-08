<?php

namespace App\Repositories;

use App\Models\Pays;
use App\Repositories\BaseRepository;

class PaysRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'libelle',
        'code_iso'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Pays::class;
    }
}
