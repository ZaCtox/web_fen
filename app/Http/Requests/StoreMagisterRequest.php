<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMagisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Si tienes policies, pon la lógica aquí; por ahora:
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255|unique:magisters,nombre',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique'   => 'Ya existe un magíster con ese nombre.',
        ];
    }
}
