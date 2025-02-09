<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    public $table = 'examen';

    public $fillable = [
        'libelle',
        'type_examen',
        'note',
        'eleve',
        'affectation'
    ];

    protected $casts = [
        'libelle' => 'string'
    ];

    public static array $rules = [
        'libelle' => 'required|string|max:100',
        'type_examen' => 'required',
        'note' => 'required',
        'eleve' => 'required',
        'affectation' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function typeExamen(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\TypeExamen::class, 'type_examen');
    }

    public function affectation()
    {
        return $this->belongsTo(AffectationMatiere::class, 'affectation_matiere');
    }

    public function effectif()
    {
        return $this->belongsTo(Effectif::class, 'effectif');
    }

    public function eleves(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Eleve::class, 'eleve');
    }
}
