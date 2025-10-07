<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformeRequest extends FormRequest
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
        $rules = [
            'nombre' => 'required|string|max:255',
            'magister_id' => 'nullable|exists:magisters,id',
        ];

        // Solo requerir archivo si no estamos editando o si se proporciona uno nuevo
        if (!$this->route('informe') || $this->hasFile('archivo')) {
            $rules['archivo'] = 'required|file|max:4096|mimes:pdf,doc,docx';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del informe es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'archivo.required' => 'Debe seleccionar un archivo.',
            'archivo.file' => 'El archivo debe ser válido.',
            'archivo.max' => 'El archivo no puede exceder los 4MB.',
            'archivo.mimes' => 'El archivo debe ser PDF, DOC o DOCX.',
            'magister_id.exists' => 'El programa seleccionado no es válido.',
        ];
    }
}
