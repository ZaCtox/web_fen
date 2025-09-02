<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMagisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('magister')?->id; // via route model binding
        return [
            'nombre' => 'required|string|max:255|unique:magisters,nombre,' . $id,
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique'   => 'Ya existe un programa con ese nombre.',
        ];
    }
}
