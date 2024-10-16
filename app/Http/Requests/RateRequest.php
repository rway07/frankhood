<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class RateRequest extends FormRequest
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
        return [
            'year' => 'required|date_format:Y',
            'quota' => 'required|numeric',
            'funeral_cost' => 'required|numeric',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'year.required' => 'Il campo anno è obbligatorio.',
            'year.date_format' => 'L\'anno nel formato sbagliato (YYYY).',
            'quota.required' => 'Il campo quota è obbligatorio.',
            'quota.numeric' => 'La quota deve essere un numero.',
            'funeral_cost.required' => 'Il campo costo funerale è obbligatorio.',
            'funeral_cost.numeric' => 'Il costo funerale deve essere un numero.',
        ];
    }
}
