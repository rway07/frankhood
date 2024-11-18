<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class ExpenseRequest extends FormRequest
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
            'description' => 'required|string',
            'amount' => 'required|numeric'
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
            'date.required' => 'Inserire la data',
            'date.date' => 'Data in formato non valido',
            'description.required' => 'Inserire la descrizione!',
            'description.string' => 'La descrizione deve essere testo',
            'amount.required' => 'Inserire l\'importo.',
            'amount.numeric' => 'L\'importo deve essere numerico'
        ];
    }
}
