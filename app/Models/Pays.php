<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    public $table = 'pays';

    public $fillable = [
        'libelle',
        'code_iso'
    ];

    protected $casts = [
        'libelle' => 'string',
        'code_iso' => 'string'
    ];

    public static array $rules = [
        'libelle' => 'required|string|max:100',
        'code_iso' => 'required|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function eleves(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Eleve::class, 'nationalite');
    }

    public function eleve1s(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Eleve::class, 'pays_residence');
    }

    public function parents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Parents::class, 'nationalite');
    }

    public function parent2s(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Parents::class, 'pays_residence');
    }
}
