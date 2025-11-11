<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHerrajeItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Forzar precio a null
        $this->merge([
            'precio' => null,
            'codigo' => null,
            'unidad' => 'UN',
            'observaciones' => null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'descripcion' => ['required', 'string', 'max:255'],
            'cantidad' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'codigo' => ['nullable'],
            'unidad' => ['nullable'],
            'precio' => ['nullable'],
            'observaciones' => ['nullable'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'descripcion.required' => 'El nombre del ítem es obligatorio',
            'descripcion.max' => 'El nombre no puede exceder 255 caracteres',
            'cantidad.required' => 'La cantidad es obligatoria',
            'cantidad.numeric' => 'La cantidad debe ser un número',
            'cantidad.min' => 'La cantidad debe ser mayor a 0',
            'cantidad.max' => 'La cantidad excede el límite permitido',
        ];
    }
}