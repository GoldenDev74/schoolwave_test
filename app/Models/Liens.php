<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liens extends Model
{
    public $table = 'liens';

    public $fillable = [
        'libelle',
        'route'
    ];

    protected $casts = [
        'libelle' => 'string',
        'route' => 'string'
    ];

    public static array $rules = [
        'libelle' => 'nullable|string|max:45',
        'route' => 'nullable|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function userLiens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\UserLiens::class, 'lien');
    }
}
