<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
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
    // app/Http/Requests/UpdateClaseRequest.php
    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $data = $this->validated() ?: $this->all();
            $id = $this->route('clase')?->id; // depende de tu binding

            if (($data['modality'] ?? null) === 'online' || empty($data['room_id'])) {
                return;
            }

            $hayChoque = \App\Models\Clase::query()
                ->where('room_id', $data['room_id'])
                ->where('period_id', $data['period_id'])
                ->where('dia', $data['dia'])
                ->where(function ($q) use ($data) {
                    $q->where('hora_inicio', '<', $data['hora_fin'])
                        ->where('hora_fin', '>', $data['hora_inicio']);
                })
                ->when($id, fn($q) => $q->where('id', '!=', $id))
                ->exists();

            if ($hayChoque) {
                $v->errors()->add('room_id', 'La sala está ocupada en ese horario.');
            }
        });
    }

}
