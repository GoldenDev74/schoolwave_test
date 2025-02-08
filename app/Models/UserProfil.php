<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfil extends Model
{
    public $table = 'user_profil';

    public $fillable = [
        'personnel',
        'profil',
        'parent',
        'eleve',
        'user'
    ];

    protected $casts = [
        
    ];

    public static array $rules = [
        'personnel' => 'nullable',
        'profil' => 'nullable',
        'parent' => 'nullable',
        'eleve' => 'nullable',
        'user' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function eleve(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Eleve::class, 'eleve');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Parent::class, 'parent');
    }

    public function personnel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Enseignant::class, 'personnel');
    }

    public function profil(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Profil::class, 'profil');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user');
    }
}
