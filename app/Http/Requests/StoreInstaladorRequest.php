<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstaladorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ajusta a tu Policy si corresponde
    }

    public function rules(): array
    {
        return [
            'usuario'  => ['required','string','max:100','unique:sh_instalador,usuario'],
            'nombre'   => ['required','string','max:150'],
            'telefono' => ['nullable','string','max:30'],
            'correo'   => ['required','email','max:150','unique:sh_instalador,correo'],
            'rut'      => ['required','string','max:20','unique:sh_instalador,rut'],
            'password' => ['required','string','min:6','max:255'],
            'activo'   => ['nullable','in:S,N'],
            'rol'      => ['required','in:admin,supervisor,instalador'],
        ];
    }
}
