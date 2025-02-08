<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    public $table = 'eleve';

    public $fillable = [
        'nom_prenom',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'pays_residence',
        'telephone',
        'email',
        'sexe',
        'parent'
    ];

    protected $casts = [
        'nom_prenom' => 'string',
        'date_naissance' => 'date',
        'lieu_naissance' => 'string',
        'nationalite' => 'integer',
        'pays_residence' => 'integer',
        'telephone' => 'string',
        'email' => 'string',
        'sexe' => 'integer',
        'parent' => 'integer'
    ];

    public static array $rules = [
        'nom_prenom' => 'required|string|max:100',
        'date_naissance' => 'required',
        'lieu_naissance' => 'nullable|string|max:100',
        'nationalite' => 'nullable',
        'pays_residence' => 'nullable',
        'telephone' => 'nullable|string|max:100',
        'email' => 'nullable|string|max:100',
        'sexe' => 'nullable',
        'parent' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function nationalites(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pays::class, 'nationalite', 'id');
    }

    public function parents(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Parents::class, 'parent', 'id');
    }

    public function paysResidence(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pays::class, 'pays_residence', 'id');
    }

    public function sexes(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sexe::class, 'sexe', 'id');
    }

    public function effectifs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Effectif::class, 'eleve');
    }
}
