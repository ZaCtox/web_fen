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
            'encargado' => 'required|string|max:150',
            'room_id' => 'nullable|exists:rooms,id',
            'url_zoom' => 'nullable|url',
        ];
    }
}
