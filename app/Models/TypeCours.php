<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeCours extends Model
{
    public $table = 'type_cours';

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
        return $this->hasMany(\App\Models\AffectationMatiere::class, 'type_cours');
    }

    public function classes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Classe::class, 'type_cours');
    }

    public function horaires(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Horaire::class, 'type_cours');
    }
}
