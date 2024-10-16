<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
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
            'date.required' => 'Date is required.',
            'date.date' => 'Date is not valid.',
            'description.required' => 'Description is required.',
            'description.string' => 'Description is not valid.',
            'amount.required' => 'Amount is required.',
            'amount.numeric' => 'Amount must be a number.',

        ];
    }
}
