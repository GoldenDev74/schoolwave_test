<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    public $table = 'classe';

    public $fillable = [
        'libelle',
        'type_cours',
        'salle'
    ];

    protected $casts = [
        'libelle' => 'string'
    ];

    public static array $rules = [
        'libelle' => 'required|string|max:100',
        'type_cours' => 'required',
        'salle' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function salles(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Salles::class, 'salle');
    }

    public function typeCourss(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\TypeCours::class, 'type_cours');
    }

    public function affectationMatieres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\AffectationMatiere::class, 'classe');
    }
}
