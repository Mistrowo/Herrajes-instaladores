<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstaladorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('instalador')->id ?? null;

        return [
            'usuario'  => ['required','string','max:100', Rule::unique('sh_instalador','usuario')->ignore($id)],
            'nombre'   => ['required','string','max:150'],
            'telefono' => ['nullable','string','max:30'],
            'correo'   => ['required','email','max:150', Rule::unique('sh_instalador','correo')->ignore($id)],
            'rut'      => ['required','string','max:20', Rule::unique('sh_instalador','rut')->ignore($id)],
            'password' => ['nullable','string','min:6','max:255'],
            'activo'   => ['nullable','in:S,N'],
            'rol'      => ['required','in:admin,supervisor,instalador'],
        ];
    }
}
