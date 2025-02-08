<?php

namespace App\Http\Requests;

use App\Models\Correspondance;
use Illuminate\Foundation\Http\FormRequest;

class CreateCorrespondanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(Correspondance::$rules, [
            // Override si nécessaire
            'destinataire' => 'required|string', // Désactive la validation
        ]);
    }
}
