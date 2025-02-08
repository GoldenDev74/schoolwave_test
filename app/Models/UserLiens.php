<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLiens extends Model
{
    public $table = 'user_liens';

    public $fillable = [
        'lien',
        'user'
    ];

    protected $casts = [
        
    ];

    public static array $rules = [
        'lien' => 'nullable',
        'user' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function lien(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Lien::class, 'lien');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user');
    }
}
