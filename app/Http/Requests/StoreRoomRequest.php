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
            // Campos booleanos de equipamiento - aceptar valores de checkbox HTML
            'calefaccion' => 'nullable|in:on,1,true',
            'energia_electrica' => 'nullable|in:on,1,true',
            'existe_aseo' => 'nullable|in:on,1,true',
            'plumones' => 'nullable|in:on,1,true',
            'borrador' => 'nullable|in:on,1,true',
            'pizarra_limpia' => 'nullable|in:on,1,true',
            'computador_funcional' => 'nullable|in:on,1,true',
            'cables_computador' => 'nullable|in:on,1,true',
            'control_remoto_camara' => 'nullable|in:on,1,true',
            'televisor_funcional' => 'nullable|in:on,1,true',
        ];
    }

}
