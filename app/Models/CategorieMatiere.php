<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieMatiere extends Model
{
    public $table = 'categorie_matiere';

    public $fillable = [
        'libelle'
    ];

    protected $casts = [
        'libelle' => 'string'
    ];

    public static array $rules = [
        'libelle' => 'required|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function matieres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Matiere::class, 'categorie_matiere');
    }
}
