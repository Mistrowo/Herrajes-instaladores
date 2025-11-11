<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHerrajeHeaderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'instalador_id' => ['nullable', 'exists:sh_instalador,id'],
            'estado' => ['required', 'in:en_revision,aprobado,rechazado'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'instalador_id.exists' => 'El instalador seleccionado no existe',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado seleccionado no es válido',
            'observaciones.max' => 'Las observaciones no pueden exceder 2000 caracteres',
        ];
    }
}