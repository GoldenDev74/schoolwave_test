<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnneeScolaire extends Model
{
    public $table = 'annee_scolaire';

    public $fillable = [
        'libelle',
        'en_cours'
    ];

    protected $casts = [
        'libelle' => 'string',
        'en_cours' => 'boolean'
    ];

    public static array $rules = [
        'libelle' => 'required|string|max:100',
        'en_cours' => 'nullable|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function affectationMatieres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\AffectationMatiere::class, 'annee_scolaire');
    }
}
