<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JourSemaine extends Model
{
    public $table = 'jour_semaine';

    public $fillable = [
        'libelle'
    ];

    protected $casts = [
        'libelle' => 'string'
    ];

    public static array $rules = [
        'libelle' => 'required|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function affectationMatieres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\AffectationMatiere::class, 'jour');
    }
}
