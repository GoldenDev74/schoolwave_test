<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    public $table = 'parent';

    public $fillable = [
        'nom_prenom',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'adresse',
        'ville',
        'pays_residence',
        'telephone',
        'email',
        'lien_eleve'
    ];

    protected $casts = [
        'nom_prenom' => 'string',
        'date_naissance' => 'date',
        'lieu_naissance' => 'string',
        'adresse' => 'string',
        'ville' => 'string',
        'telephone' => 'string',
        'email' => 'string'
    ];

    public static array $rules = [
        'nom_prenom' => 'required|string|max:100',
        'date_naissance' => 'required',
        'lieu_naissance' => 'nullable|string|max:100',
        'nationalite' => 'nullable',
        'adresse' => 'required|string|max:100',
        'ville' => 'required|string|max:100',
        'pays_residence' => 'nullable',
        'telephone' => 'required|string|max:100',
        'email' => 'required|string|max:100',
        'lien_eleve' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function lienEleves(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\LienParentEleve::class, 'lien_eleve');
    }

    public function nationalites(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Pays::class, 'nationalite');
    }

    public function paysResidences(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Pays::class, 'pays_residence');
    }

    public function eleves(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Eleve::class, 'parent');
    }
}
