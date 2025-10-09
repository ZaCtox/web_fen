<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\ClaseSesion;
use Illuminate\Http\Request;

class ClaseSesionController extends Controller
{
    /**
     * Almacenar una nueva sesión
     */
    public function store(Request $request, Clase $clase)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'url_grabacion' => 'nullable|url',
            'estado' => 'required|in:pendiente,completada,cancelada',
            'observaciones' => 'nullable|string|max:500'
        ]);

        $validated['clase_id'] = $clase->id;

        ClaseSesion::create($validated);

        return redirect()->route('clases.show', $clase)
            ->with('success', 'Sesión agregada correctamente.');
    }

    /**
     * Actualizar una sesión existente
     */
    public function update(Request $request, ClaseSesion $sesion)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'url_grabacion' => 'nullable|url',
            'estado' => 'required|in:pendiente,completada,cancelada',
            'observaciones' => 'nullable|string|max:500'
        ]);

        $sesion->update($validated);

        return redirect()->route('clases.show', $sesion->clase)
            ->with('success', 'Sesión actualizada correctamente.');
    }

    /**
     * Actualizar solo la grabación de una sesión
     */
    public function updateGrabacion(Request $request, ClaseSesion $sesion)
    {
        $validated = $request->validate([
            'url_grabacion' => 'required|url'
        ]);

        $sesion->update([
            'url_grabacion' => $validated['url_grabacion'],
            'estado' => 'completada'
        ]);

        return redirect()->route('clases.show', $sesion->clase)
            ->with('success', 'Grabación agregada correctamente.');
    }

    /**
     * Eliminar una sesión
     */
    public function destroy(ClaseSesion $sesion)
    {
        $clase = $sesion->clase;
        $sesion->delete();

        return redirect()->route('clases.show', $clase)
            ->with('success', 'Sesión eliminada correctamente.');
    }

    /**
     * Generar sesiones automáticamente basándose en el período de la clase
     */
    public function generarSesiones(Request $request, Clase $clase)
    {
        // Cargar el período si no está cargado
        if (!$clase->relationLoaded('period')) {
            $clase->load('period');
        }

        if (!$clase->period) {
            return back()->with('error', 'La clase no tiene un período asignado.');
        }

        $period = $clase->period;
        
        // Si no hay fechas en el período
        if (!$period->fecha_inicio || !$period->fecha_fin) {
            return back()->with('error', 'El período no tiene fechas de inicio y fin definidas.');
        }

        $fechaInicio = \Carbon\Carbon::parse($period->fecha_inicio);
        $fechaFin = \Carbon\Carbon::parse($period->fecha_fin);
        
        // Convertir día de la clase a número (0=Domingo, 5=Viernes, 6=Sábado)
        $diaSemana = match($clase->dia) {
            'Viernes' => 5,
            'Sábado' => 6,
            default => null
        };

        if (!$diaSemana) {
            return back()->with('error', 'Día de clase no válido.');
        }

        $sesionesCreadas = 0;
        $current = $fechaInicio->copy();

        // Ajustar al primer día que coincida con el día de la clase
        while ($current->dayOfWeek !== $diaSemana && $current->lte($fechaFin)) {
            $current->addDay();
        }

        // Generar sesiones cada semana
        while ($current->lte($fechaFin)) {
            // Verificar que no exista ya una sesión en esta fecha
            $existe = ClaseSesion::where('clase_id', $clase->id)
                ->where('fecha', $current->toDateString())
                ->exists();

            if (!$existe) {
                ClaseSesion::create([
                    'clase_id' => $clase->id,
                    'fecha' => $current->toDateString(),
                    'estado' => 'pendiente'
                ]);
                $sesionesCreadas++;
            }
            
            // Avanzar una semana (7 días)
            $current->addWeek();
        }

        return redirect()->route('clases.show', $clase)
            ->with('success', "Se generaron {$sesionesCreadas} sesiones automáticamente para el período {$period->nombre_completo}.");
    }
}

