<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsignarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'nota_venta' => 'required|string|max:255',
            'solicita' => 'nullable|string|max:255',
            'asignado1' => 'nullable|exists:sh_instalador,id',
            'asignado2' => 'nullable|exists:sh_instalador,id|different:asignado1',
            'asignado3' => 'nullable|exists:sh_instalador,id|different:asignado1,asignado2',
            'asignado4' => 'nullable|exists:sh_instalador,id|different:asignado1,asignado2,asignado3',
            'fecha_asigna' => 'required|date',
            'observaciones' => 'nullable|string|max:1000',
        ];

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nota_venta.required' => 'La nota de venta es obligatoria.',
            'nota_venta.string' => 'La nota de venta debe ser texto.',
            'nota_venta.max' => 'La nota de venta no puede exceder 255 caracteres.',
            
            'asignado1.exists' => 'El instalador 1 seleccionado no existe.',
            'asignado2.exists' => 'El instalador 2 seleccionado no existe.',
            'asignado2.different' => 'El instalador 2 debe ser diferente al instalador 1.',
            'asignado3.exists' => 'El instalador 3 seleccionado no existe.',
            'asignado3.different' => 'El instalador 3 debe ser diferente a los anteriores.',
            'asignado4.exists' => 'El instalador 4 seleccionado no existe.',
            'asignado4.different' => 'El instalador 4 debe ser diferente a los anteriores.',
            
            'fecha_asigna.required' => 'La fecha de asignación es obligatoria.',
            'fecha_asigna.date' => 'La fecha de asignación debe ser una fecha válida.',
            
            'observaciones.max' => 'Las observaciones no pueden exceder 1000 caracteres.',
        ];
    }

   
    protected function prepareForValidation(): void
    {
        $this->merge([
            'asignado1' => $this->asignado1 ?: null,
            'asignado2' => $this->asignado2 ?: null,
            'asignado3' => $this->asignado3 ?: null,
            'asignado4' => $this->asignado4 ?: null,
            'observaciones' => $this->observaciones ?: null,
            'solicita' => $this->solicita ?: auth()->user()->nombre,
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'nota_venta' => 'nota de venta',
            'asignado1' => 'instalador 1',
            'asignado2' => 'instalador 2',
            'asignado3' => 'instalador 3',
            'asignado4' => 'instalador 4',
            'fecha_asigna' => 'fecha de asignación',
            'observaciones' => 'observaciones',
        ];
    }
}