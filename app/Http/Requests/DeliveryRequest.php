<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class DeliveryRequest extends FormRequest
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
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'total' => 'numeric',
            'remaining' => 'numeric'
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'date.required' => 'Inserire la data',
            'date.date' => 'Data in formato non valido',
            'amount.required' => 'Inserire la cifra della consegna',
            'amount.numeric' => 'La cifra deve essere numerica',
            'total.numeric' => 'Il totale deve essere numerico',
            'remaining.numeric' => 'La cifra rimanente deve essere numerica'
        ];
    }
}
