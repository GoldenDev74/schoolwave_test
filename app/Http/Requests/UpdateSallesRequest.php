<?php

namespace App\Http\Requests;

use App\Models\Salles;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSallesRequest extends FormRequest
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
        $rules = Salles::$rules;
        
        return $rules;
    }
}
