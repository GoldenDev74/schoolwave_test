<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeExamen extends Model
{
    public $table = 'type_examen';

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

    public function examen(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Examen::class, 'type_examen');
    }
}
