<?php

namespace App\Repositories;

use App\Models\Profil;
use App\Repositories\BaseRepository;

class ProfilRepository extends BaseRepository
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
        return Profil::class;
    }
}
