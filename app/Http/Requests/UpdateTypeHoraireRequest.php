<?php

namespace App\Http\Requests;

use App\Models\TypeHoraire;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTypeHoraireRequest extends FormRequest
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
        $rules = TypeHoraire::$rules;
        
        return $rules;
    }
}
