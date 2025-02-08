<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LienParentEleve extends Model
{
    public $table = 'lien_parent_eleve';

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

    public function parents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Parents::class, 'lien_eleve');
    }
}
