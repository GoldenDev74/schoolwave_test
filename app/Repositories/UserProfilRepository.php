<?php

namespace App\Repositories;

use App\Models\UserProfil;
use App\Repositories\BaseRepository;

class UserProfilRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'personnel',
        'profil',
        'parent',
        'eleve',
        'user'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return UserProfil::class;
    }
}
