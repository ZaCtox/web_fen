<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClaseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tipo' => $this->tipo ?? null,
            'encargado' => $this->encargado ?? null,
            'modality' => $this->modality ?? null,
            'dia' => $this->dia ?? null,
            'hora_inicio' => $this->hora_inicio ?? null,
            'hora_fin' => $this->hora_fin ?? null,
            'url_zoom' => $this->url_zoom ?? null,
            'course' => $this->course ? [
                'id' => $this->course->id,
                'nombre' => $this->course->nombre ?? null,
                'magister' => $this->course->magister ? [
                    'id' => $this->course->magister->id,
                    'nombre' => $this->course->magister->nombre ?? null,
                ] : null,
            ] : null,
            'period' => $this->period ? [
                'id' => $this->period->id,
                'anio' => $this->period->anio,
                'numero' => $this->period->numero,
                'nombre_completo' => $this->period->nombre_completo ?? null,
            ] : null,
            'room' => $this->room ? [
                'id' => $this->room->id,
                'name' => $this->room->name ?? null,
            ] : null,
        ];
    }
}
