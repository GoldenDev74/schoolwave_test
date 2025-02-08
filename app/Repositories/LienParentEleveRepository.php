<?php

namespace App\Repositories;

use App\Models\LienParentEleve;
use App\Repositories\BaseRepository;

class LienParentEleveRepository extends BaseRepository
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
        return LienParentEleve::class;
    }
}
