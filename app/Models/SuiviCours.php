<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuiviCours extends Model
{
    public $table = 'suivi_cours';

    public $fillable = [
        'date',
        'titre',
        'resume',
        'observation',
        'affection_matiere'
    ];

    protected $casts = [
        'date' => 'date',
        'titre' => 'string',
        'resume' => 'string',
        'observation' => 'string'
    ];

    public static array $rules = [
        'date' => 'required',
        'titre' => 'required|string|max:100',
        'resume' => 'required|string|max:100',
        'observation' => 'required|string|max:100',
        'affection_matiere' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function affectionMatiere(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\AffectationMatiere::class, 'affection_matiere');
    }
}
