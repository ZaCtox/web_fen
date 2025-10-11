<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Información general de la clase
            'course_id' => 'required|exists:courses,id',
            'period_id' => 'required|exists:periods,id',
            'encargado' => 'required|string|max:150',
            'room_id' => 'nullable|exists:rooms,id',
            'url_zoom' => 'nullable|url',
            
            // Validación de sesiones
            'sesiones' => 'required|array|min:1',
            'sesiones.*.fecha' => 'required|date',
            'sesiones.*.dia' => 'required|in:Viernes,Sábado',
            'sesiones.*.hora_inicio' => 'required|date_format:H:i',
            'sesiones.*.hora_fin' => 'required|date_format:H:i|after:sesiones.*.hora_inicio',
            'sesiones.*.modalidad' => 'required|in:presencial,online,hibrida',
            'sesiones.*.room_id' => 'nullable|exists:rooms,id',
            'sesiones.*.url_zoom' => 'nullable|url',
            'sesiones.*.observaciones' => 'nullable|string|max:500',
            'sesiones.*.estado' => 'nullable|in:pendiente,completada,cancelada',
            'sesiones.*.numero_sesion' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'sesiones.*.hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'sesiones.*.modalidad.required' => 'La modalidad es obligatoria para cada sesión.',
            'sesiones.*.fecha.required' => 'La fecha es obligatoria para cada sesión.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $data = $this->all();
            $sesiones = $data['sesiones'] ?? [];

            foreach ($sesiones as $index => $sesion) {
                $modalidad = $sesion['modalidad'] ?? null;

                // Validar Zoom según modalidad
                if (in_array($modalidad, ['online', 'hibrida'])) {
                    $zoomSesion = $sesion['url_zoom'] ?? null;
                    $zoomGlobal = $data['url_zoom'] ?? null;
                    
                    if (empty($zoomSesion) && empty($zoomGlobal)) {
                        $v->errors()->add(
                            "sesiones.{$index}.url_zoom",
                            'El enlace de Zoom es obligatorio para sesiones Online e Híbridas. Define uno global o específico para esta sesión.'
                        );
                    }
                }

                // Validar sala según modalidad
                if (in_array($modalidad, ['presencial', 'hibrida'])) {
                    $roomSesion = $sesion['room_id'] ?? null;
                    $roomGlobal = $data['room_id'] ?? null;
                    
                    // La sala es opcional, pero si hay choque de horarios, debe reportarse
                    $roomToCheck = $roomSesion ?: $roomGlobal;
                    
                    if ($roomToCheck) {
                        // Validar conflictos de horario en la sala
                        $hayChoque = \App\Models\ClaseSesion::query()
                            ->where('room_id', $roomToCheck)
                            ->where('fecha', $sesion['fecha'])
                            ->where(function ($q) use ($sesion) {
                                $q->where('hora_inicio', '<', $sesion['hora_fin'])
                                    ->where('hora_fin', '>', $sesion['hora_inicio']);
                            })
                            ->exists();

                        if ($hayChoque) {
                            $v->errors()->add(
                                "sesiones.{$index}.room_id",
                                'La sala está ocupada en ese horario y fecha.'
                            );
                        }
                    }
                }

                // Validar que hora_fin sea posterior a hora_inicio
                if (isset($sesion['hora_inicio']) && isset($sesion['hora_fin'])) {
                    if ($sesion['hora_inicio'] >= $sesion['hora_fin']) {
                        $v->errors()->add(
                            "sesiones.{$index}.hora_fin",
                            'La hora de fin debe ser posterior a la hora de inicio.'
                        );
                    }
                }
            }
        });
    }
}
