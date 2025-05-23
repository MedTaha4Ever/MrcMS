<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Assuming admin users are authorized, or implement specific logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'mat' => 'required|string|max:255|unique:cars,mat',
            'modele_id' => 'required|integer|exists:modeles,id',
            'dpc' => 'required|date',
            'contract_id' => 'nullable|integer|exists:contracts,id', // Assuming 0 is not a valid contract id, and it should be nullable if no contract
            'km' => 'required|integer|min:0',
        ];
    }
}
