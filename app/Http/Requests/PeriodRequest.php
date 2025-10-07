<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeriodRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'anio' => 'required|integer|min:1|max:2',
            'numero' => 'required|integer|between:1,6',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'activo' => 'boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'anio.required' => 'El año académico es requerido.',
            'anio.integer' => 'El año académico debe ser un número entero.',
            'anio.min' => 'El año académico debe ser al menos 1.',
            'anio.max' => 'El año académico no puede ser mayor a 2.',
            'numero.required' => 'El trimestre es requerido.',
            'numero.integer' => 'El trimestre debe ser un número entero.',
            'numero.between' => 'El trimestre debe estar entre 1 y 6.',
            'fecha_inicio.required' => 'La fecha de inicio es requerida.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fecha_fin.required' => 'La fecha de término es requerida.',
            'fecha_fin.date' => 'La fecha de término debe ser una fecha válida.',
            'fecha_fin.after' => 'La fecha de término debe ser posterior a la fecha de inicio.',
            'activo.boolean' => 'El estado activo debe ser verdadero o falso.'
        ];
    }
}
