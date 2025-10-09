<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    } // lo maneja el middleware
    public function rules(): array
    {
        return [
            'course_id' => 'required|exists:courses,id',
            'period_id' => 'required|exists:periods,id',
            'tipo' => 'nullable|string|max:150',
            'modality' => 'required|in:presencial,online,híbrida',
            'room_id' => 'nullable|exists:rooms,id',
            'dia' => 'required|in:Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'url_zoom' => 'nullable|url',
            'encargado' => 'nullable|string|max:150',
        ];
    }
    // app/Http/Requests/StoreClaseRequest.php
    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $data = $this->validated() ?: $this->all();

            // Validar que url_zoom sea requerido en online e híbrida
            $modality = $data['modality'] ?? null;
            if (($modality === 'online' || $modality === 'hibrida') && empty($data['url_zoom'])) {
                $v->errors()->add('url_zoom', 'El enlace de Zoom es obligatorio para clases Online e Híbridas.');
            }

            // Validar sala solo si no es online
            if ($modality === 'online' || empty($data['room_id'])) {
                return; // no aplica sala
            }

            $hayChoque = \App\Models\Clase::query()
                ->where('room_id', $data['room_id'])
                ->where('period_id', $data['period_id'])
                ->where('dia', $data['dia'])
                ->where(function ($q) use ($data) {
                    $q->where('hora_inicio', '<', $data['hora_fin'])
                        ->where('hora_fin', '>', $data['hora_inicio']);
                })
                ->exists();

            if ($hayChoque) {
                $v->errors()->add('room_id', 'La sala está ocupada en ese horario.');
            }
        });
    }

}
