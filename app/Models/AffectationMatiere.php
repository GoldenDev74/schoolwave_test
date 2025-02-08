<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AffectationMatiere extends Model
{
    protected $table = 'affectation_matiere';

    protected $fillable = [
        'classe',
        'annee_scolaire',
        'matiere',
        'enseignant',
        'horaire',
        'type_cours',
        'jour',
        'mode_affection',
        'debut',
        'fin',
        'annulation'
    ];

    protected $with = [
        'enseignant',
        'classe',
        'matiere',
        'typeCours',
        'horaire',
        'jour',
        'modeAffectation'
    ];

    protected $casts = [
        'debut' => 'date',
        'fin' => 'date',
        'enseignant' => 'integer',
        'classe' => 'integer',
        'matiere' => 'integer',
        'horaire' => 'integer',
        'jour' => 'integer',
        'type_cours' => 'integer',
        'mode_affection' => 'integer',
        'annee_scolaire' => 'integer',
        'annulation' => 'boolean'
    ];

    public static array $rules = [
        'classe' => 'required',
        'annee_scolaire' => 'required',
        'matiere' => 'required',
        'enseignant' => 'required',
        'horaire' => 'required',
        'type_cours' => 'required',
        'jour' => 'required',
        'mode_affection' => 'required|in:1,2',
        'debut' => 'nullable|date',
        'fin' => 'nullable|date|after_or_equal:debut'
    ];

    // Relations
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'enseignant', 'id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe', 'id');
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'matiere', 'id');
    }

    public function typeCours()
    {
        return $this->belongsTo(TypeCours::class, 'type_cours', 'id');
    }

    public function horaire()
    {
        return $this->belongsTo(Horaire::class, 'horaire', 'id');
    }

    public function jour()
    {
        return $this->belongsTo(JourSemaine::class, 'jour', 'id');
    }

    public function modeAffectation()
    {
        return $this->belongsTo(ModeAffectation::class, 'mode_affection', 'id');
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire', 'id');
    }

    public function controles()
    {
        return $this->hasMany(Controle::class, 'affectation_cours');
    }

    public function suiviCours()
    {
        return $this->hasMany(SuiviCours::class, 'affection_matiere');
    }

    // Mutator pour annulation
    public function setAnnulationAttribute($value)
    {
        // Convertit la valeur en booléen puis en entier (1 ou 0)
        $this->attributes['annulation'] = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    }

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            $enseignantId = $model->getAttribute('enseignant');
            $enseignantObj = Enseignant::find($enseignantId);
            
            Log::info('AffectationMatiere retrieved:', [
                'id' => $model->id,
                'raw_enseignant_id' => $enseignantId,
                'enseignant_details' => $enseignantObj ? [
                    'id' => $enseignantObj->id,
                    'nom_prenom' => $enseignantObj->nom_prenom
                ] : null
            ]);
        });
    }
}