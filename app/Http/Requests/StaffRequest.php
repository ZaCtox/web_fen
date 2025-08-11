<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('staff')?->id; // null en create

        return [
            'nombre'   => ['required','string','max:150'],
            'cargo'    => ['required','string','max:150'],
            'telefono' => ['nullable','string','max:30'],
            'email'    => ['required','email','max:150','unique:staff,email,'.($id ?? 'NULL').',id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'cargo' => 'cargo',
            'telefono' => 'teléfono',
            'email' => 'correo electrónico',
        ];
    }
}
