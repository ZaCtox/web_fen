<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            // Campos booleanos de equipamiento
            'calefaccion' => 'nullable|boolean',
            'energia_electrica' => 'nullable|boolean',
            'existe_aseo' => 'nullable|boolean',
            'plumones' => 'nullable|boolean',
            'borrador' => 'nullable|boolean',
            'pizarra_limpia' => 'nullable|boolean',
            'computador_funcional' => 'nullable|boolean',
            'cables_computador' => 'nullable|boolean',
            'control_remoto_camara' => 'nullable|boolean',
            'televisor_funcional' => 'nullable|boolean',
        ];
    }

}
