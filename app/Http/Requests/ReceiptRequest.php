<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class ReceiptRequest extends FormRequest
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
            'issue-date' => 'required|date',
            'rates' => 'required|integer',
            'recipient' => 'required|integer',
            'total' => 'required|integer'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages() {
        return [
            'issue-date.required' => 'Inserire la data della ricevuta',
            'issue-date.date' => 'Data della ricevuta nel formato sbagliato',
            'rates.required' => 'Inserire la tariffa',
            'rates.integer' => 'La tariffa deve essere un numero intero',
            'recipient.required' => 'Inserire il destinatario',
            'recipient.integer' => 'Destinatario nel formato sbagliato',
            'total.required' => 'Inserire il totale',
            'total.integer' => 'Il totale deve essere un numero intero',
        ];
    }
}
