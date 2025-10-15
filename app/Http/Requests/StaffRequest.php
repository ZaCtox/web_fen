<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $staffId = $this->route('staff')?->id; // obtiene el id del modelo Staff

        return [
            'nombre' => ['required', 'string', 'max:150'],
            'cargo' => ['required', 'string', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'anexo' => ['nullable', 'string', 'max:30'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('staff', 'email')->ignore($staffId),
            ],
            'foto' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'avatar_style' => ['nullable', 'in:icon,initials'],
            'avatar_color' => ['nullable', 'string', 'max:7'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'cargo' => 'cargo',
            'telefono' => 'teléfono',
            'anexo' => 'anexo',
            'email' => 'correo electrónico',
            'foto' => 'foto de perfil',
        ];
    }

    public function messages(): array
    {
        return [
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La foto debe ser un archivo tipo: jpeg, jpg, png o webp.',
            'foto.max' => 'La foto no debe pesar más de 2MB.',
        ];
    }
}
