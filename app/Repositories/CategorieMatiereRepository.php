<?php

namespace App\Repositories;

use App\Models\CategorieMatiere;
use App\Repositories\BaseRepository;

class CategorieMatiereRepository extends BaseRepository
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
        return CategorieMatiere::class;
    }
}
