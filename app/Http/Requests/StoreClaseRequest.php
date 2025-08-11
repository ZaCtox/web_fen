<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClaseRequest extends FormRequest
{
    public function authorize(): bool { return true; } // lo maneja el middleware
    public function rules(): array
    {
        return [
            'course_id'   => 'required|exists:courses,id',
            'period_id'   => 'required|exists:periods,id',
            'modality'    => 'required|in:presencial,online,hibrida',
            'room_id'     => 'nullable|exists:rooms,id',
            'dia'         => 'required|in:Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin'    => 'required|date_format:H:i|after:hora_inicio',
            'url_zoom'    => 'nullable|url',
        ];
    }
}
