<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Controle extends Model
{
    public $table = 'controle';

    public $fillable = [
        'effectif',
        'affectation_cours',
        'date_controle',
        'present'
    ];

    protected $casts = [
        'date_controle' => 'date',
        'present' => 'boolean'
    ];

    public static array $rules = [
        'effectif' => 'required',
        'affectation_cours' => 'required',
        'date_controle' => 'required',
        'present' => 'nullable|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function enseignants()
    {
        return $this->belongsTo(Enseignant::class, 'enseignant', 'id');
    }

    public function affectationCourss()
    {
        return $this->belongsTo(AffectationMatiere::class, 'affectation_cours', 'id');
    }

    // Relation avec Effectif
    public function effectifs()
    {
        return $this->belongsTo(Effectif::class, 'effectif');
    }
}