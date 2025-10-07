<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'magister_id' => 'required|exists:magisters,id',
            'period_id' => 'required|exists:periods,id',
            'anio' => 'nullable|integer|min:1|max:2',
            'numero' => 'nullable|integer|min:1|max:6'
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
            'nombre.required' => 'El nombre del curso es obligatorio.',
            'nombre.max' => 'El nombre del curso no puede exceder los 255 caracteres.',
            'magister_id.required' => 'Debe seleccionar un programa académico.',
            'magister_id.exists' => 'El programa académico seleccionado no es válido.',
            'period_id.required' => 'Debe seleccionar un período académico.',
            'period_id.exists' => 'El período académico seleccionado no es válido.',
            'anio.integer' => 'El año debe ser un número entero.',
            'anio.min' => 'El año debe ser al menos 1.',
            'anio.max' => 'El año no puede ser mayor a 2.',
            'numero.integer' => 'El trimestre debe ser un número entero.',
            'numero.min' => 'El trimestre debe ser al menos 1.',
            'numero.max' => 'El trimestre no puede ser mayor a 6.'
        ];
    }
}
