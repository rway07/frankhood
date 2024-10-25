<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'first_name' => 'required|',
            'last_name' => 'required|',
            'alias' => 'string|nullable',
            'cf' => 'required|alpha_num|size:16',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string',
            'birth_province' => 'required|alpha',
            'gender' => 'required',
            'address' => 'required',
            'cap' => 'required|digits:5',
            'municipality' => 'required',
            'province' => 'required',
            'phone' => '',
            'mobile_phone' => '',
            'email' => 'email|nullable',
            'enrollment_year' => 'required|digits:4',
            'death_date' => 'date|nullable',
            'revocation_date' => 'date|nullable',
            'priorato' => 'nullable'
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
            'first_name.required' => 'Inserire il nome!',
            'last_name.required' => 'Inserire il cognome!',
            'alias.required' => 'Inserire l\'alias!',
            'cf.required' => 'Inserire il codice fiscale!',
            'birth_date.required' => 'Inserire la data di nascita!',
            'birth_place.required' => 'Inserire il luogo di nascita!',
            'birth_province.required' => 'Inserire la provincia di nascita!',
            'gender.required' => 'Inserire il sesso!',
            'address.required' => 'Inserire l\'indirizzo!',
            'cap.required' => 'Inserire il CAP!',
            'municipality.required' => 'Inserire il comune!',
            'province.required' => 'Inserire la provincia!',
            'email.email' => 'Email scritta in modo errato!',
            'enrollment_year.required' => 'Inserire l\'anno di iscrizione!',
        ];
    }
}
