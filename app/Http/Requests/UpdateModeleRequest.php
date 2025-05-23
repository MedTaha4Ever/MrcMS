<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateModeleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Or add specific authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $modeleId = $this->route('modele') ? $this->route('modele')->id : $this->input('id');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('modeles')->where(function ($query) {
                    return $query->where('marque_id', $this->input('marque_id'));
                })->ignore($modeleId),
            ],
            'year' => 'nullable|string|max:255', // Consider 'digits:4'
            'marque_id' => 'required|integer|exists:marques,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'The model name must be unique for the selected marque.',
        ];
    }
}
