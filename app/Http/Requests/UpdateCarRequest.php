<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
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
        // Assumes that the car ID will be passed in the route, e.g., /cars/{car}/edit
        // Or, if 'id' is passed as a hidden input in the form.
        $carId = $this->route('car') ? $this->route('car')->id : $this->input('id');

        return [
            'mat' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cars', 'mat')->ignore($carId),
            ],
            'modele_id' => 'required|integer|exists:modeles,id',
            'dpc' => 'required|date',
            'contract_id' => 'nullable|integer|exists:contracts,id', // Assuming 0 is not a valid contract id
            'km' => 'required|integer|min:0',
            // Include 'id' if it's part of the form data and needs validation, though typically it's from the route
            // 'id' => 'required|integer|exists:cars,id', 
        ];
    }
}
