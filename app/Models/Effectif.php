<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Effectif extends Model
{
    public $table = 'effectif';

    public $fillable = [
        'annee_scolaire',
        'classe',
        'eleve'
    ];

    protected $casts = [

    ];

    public static array $rules = [
        'annee_scolaire' => 'required',
        'classe' => 'required',
        'eleve' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function anneeScolaires(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\AnneeScolaire::class, 'annee_scolaire');
    }

    public function classes(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Classe::class, 'classe');
    }

    public function eleves(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Eleve::class, 'eleve');
    }

    public function controles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Controle::class, 'effectif');
    }
}
