<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Correspondance extends Model
{
    public $table = 'correspondance';

    public $fillable = [
        'objet',
        'destinataire',
        'message',
        'expediteur',
        'cible'
    ];

    protected $casts = [
        'objet' => 'string',
        'destinataire' => 'string',
        'message' => 'string'
    ];

    public static array $rules = [
        'objet' => 'required|string|max:100',
        'destinataire' => 'required|string',
        'message' => 'required|string|max:100',
        //'expediteur' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable',
        'cible' => 'nullable'
    ];

    public function cible(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Profil::class, 'cible');
    }

    // Garder uniquement celle-ci
    public function expediteur(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'expediteur');
    }

    // Après (renommer en "sender")
    public function sender(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'expediteur');
    }

    // Relations nécessaires
    public function expediteurUser()
    {
        return $this->belongsTo(User::class, 'expediteur');
    }

    public function cibleProfil()
    {
        return $this->belongsTo(Profil::class, 'cible');
    }
}
