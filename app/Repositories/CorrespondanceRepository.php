<?php

namespace App\Repositories;

use App\Models\Correspondance;
use App\Repositories\BaseRepository;

class CorrespondanceRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'objet',
        'destinataire',
        'message',
        'expediteur',
        'cible'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Correspondance::class;
    }
}
