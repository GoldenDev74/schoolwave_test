<?php

namespace App\Repositories;

use App\Models\UserLiens;
use App\Repositories\BaseRepository;

class UserLiensRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'lien',
        'user'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return UserLiens::class;
    }
}
