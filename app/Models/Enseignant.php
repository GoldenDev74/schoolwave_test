<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    public $table = 'enseignant';

    public $fillable = [
        'nom_prenom',
        'date_naissance',
        'date_engagement',
        'date_diplome',
        'diplome',
        'filiere',
        'sexe',
        'type_cours',
        'nationalite',
        'enseignant',
        'administration',
        'type_personnel',
        'email'
    ];

    protected $casts = [
        'nom_prenom' => 'string',
        'date_naissance' => 'date',
        'date_engagement' => 'date',
        'date_diplome' => 'date',
        'enseignant' => 'boolean',
        'administration' => 'boolean',
        'email' => 'string'
    ];

    public static array $rules = [
        'nom_prenom' => 'required|string|max:100',
        'date_naissance' => 'required',
        'date_engagement' => 'required',
        'date_diplome' => 'required',
        'diplome' => 'required',
        'filiere' => 'required',
        'sexe' => 'nullable',
        'type_cours' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable',
        'nationalite' => 'nullable',
        'enseignant' => 'nullable|boolean',
        'administration' => 'nullable|boolean',
        'type_personnel' => 'nullable',
        'email' => 'nullable|string|max:100'
    ];

    public function diplomes(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Diplome::class, 'diplome');
    }

    public function filieres(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Filere::class, 'filiere');
    }

    public function sexes(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Sexe::class, 'sexe');
    }

    public function typeCours(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\TypeCours::class, 'type_cours');
    }

    public function typePersonnel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\TypePersonnel::class, 'type_personnel');
    }

    public function affectationMatieres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\AffectationMatiere::class, 'enseignant');
    }

    public function userProfils(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\UserProfil::class, 'personnel');
    }
}
