<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
<<<<<<< Updated upstream
=======
use Illuminate\Validation\Rule;
>>>>>>> Stashed changes

class StaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
<<<<<<< Updated upstream
        $id = $this->route('id');
=======
        $staffId = $this->route('staff')?->id; // obtiene el id del modelo Staff
>>>>>>> Stashed changes

        return [
            'nombre' => ['required', 'string', 'max:150'],
            'cargo' => ['required', 'string', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
<<<<<<< Updated upstream
            'email' => ['required', 'email', 'max:150', 'unique:staff,email,' . ($id ?? 'NULL') . ',id'],
=======
            'anexo' => ['nullable', 'string', 'max:30'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('staff', 'email')->ignore($staffId),
            ],
>>>>>>> Stashed changes
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'cargo' => 'cargo',
            'telefono' => 'teléfono',
<<<<<<< Updated upstream
=======
            'anexo' => 'anexo',
>>>>>>> Stashed changes
            'email' => 'correo electrónico',
        ];
    }
}
