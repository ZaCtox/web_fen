<?php

namespace App\Http\Controllers;

use App\Models\Magister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreMagisterRequest;
use App\Http\Requests\UpdateMagisterRequest;
use Exception;

class MagisterController extends Controller
{
    /**
     * Mostrar listado de programas de magíster
     */
    public function index(Request $request)
    {
        try {
            $query = Magister::query()->withCount('courses');

            // Búsqueda por nombre
            if ($request->filled('q')) {
                $query->where('nombre', 'like', '%' . $request->q . '%');
            }

            // Ordenamiento
            $sortBy = $request->get('sort', 'nombre');
            $sortDirection = $request->get('direction', 'asc');

            $magisters = $query->orderBy($sortBy, $sortDirection)
                ->paginate(10)
                ->withQueryString();

            return view('magisters.index', compact('magisters'));

        } catch (Exception $e) {
            Log::error('Error al cargar listado de magísteres: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar el listado de programas.');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            return view('magisters.create');
        } catch (Exception $e) {
            Log::error('Error al cargar formulario de creación de magíster: ' . $e->getMessage());
            return redirect()->route('magisters.index')->with('error', 'Error al cargar el formulario.');
        }
    }

    /**
     * Almacenar nuevo programa de magíster
     */
    public function store(StoreMagisterRequest $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255|unique:magisters,nombre',
                'color' => 'nullable|string|max:7',
                'encargado' => 'nullable|string|max:255',
                'asistente' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'anexo' => 'nullable|string|max:20',
                'correo' => 'nullable|email|max:255',
            ]);

            // Validar formato de color hexadecimal
            if (!empty($validated['color']) && !preg_match('/^#[0-9A-Fa-f]{6}$/', $validated['color'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['color' => 'El color debe estar en formato hexadecimal (#RRGGBB).']);
            }

            $magister = Magister::create($validated);

            Log::info('Nuevo magíster creado', ['magister_id' => $magister->id, 'nombre' => $magister->nombre]);

            return redirect()
                ->route('magisters.index')
                ->with('success', 'Programa "' . $magister->nombre . '" creado correctamente.');

        } catch (Exception $e) {
            Log::error('Error al crear magíster: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el programa. Por favor, inténtelo nuevamente.');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Magister $magister)
    {
        try {
            $magister->loadCount('courses');
            return view('magisters.edit', compact('magister'));
        } catch (Exception $e) {
            Log::error('Error al cargar formulario de edición de magíster: ' . $e->getMessage());
            return redirect()->route('magisters.index')->with('error', 'Error al cargar el formulario de edición.');
        }
    }

    /**
     * Actualizar programa de magíster
     */
    public function update(UpdateMagisterRequest $request, Magister $magister)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255|unique:magisters,nombre,' . $magister->id,
                'color' => 'nullable|string|max:7',
                'encargado' => 'nullable|string|max:255',
                'asistente' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'anexo' => 'nullable|string|max:20',
                'correo' => 'nullable|email|max:255',
            ]);

            // Validar formato de color hexadecimal
            if (!empty($validated['color']) && !preg_match('/^#[0-9A-Fa-f]{6}$/', $validated['color'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['color' => 'El color debe estar en formato hexadecimal (#RRGGBB).']);
            }

            $nombreAnterior = $magister->nombre;
            $magister->update($validated);

            Log::info('Magíster actualizado', [
                'magister_id' => $magister->id,
                'nombre_anterior' => $nombreAnterior,
                'nombre_nuevo' => $magister->nombre
            ]);

            return redirect()
                ->route('magisters.index')
                ->with('success', 'Programa "' . $magister->nombre . '" actualizado correctamente.');

        } catch (Exception $e) {
            Log::error('Error al actualizar magíster: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el programa. Por favor, inténtelo nuevamente.');
        }
    }

    /**
     * Eliminar programa de magíster
     */
    public function destroy(Magister $magister)
    {
        try {
            $nombre = $magister->nombre;
            $cantidadCursos = $magister->courses()->count();
            $cantidadNovedades = $magister->novedades()->count();
            $cantidadInformes = $magister->informes()->count();
            $cantidadEventos = $magister->events()->count();

            DB::transaction(function () use ($magister) {
                // 1. Eliminar clases asociadas a los cursos del magister
                if ($magister->courses()->exists()) {
                    foreach ($magister->courses as $course) {
                        $course->clases()->delete();
                    }
                    // Eliminar los cursos
                    $magister->courses()->delete();
                }
                
                // 2. Las novedades, informes y eventos se pondrán en NULL automáticamente
                // por las foreign keys con onDelete('set null') / nullOnDelete()
                
                // 3. Finalmente eliminar el magíster
                $magister->delete();
            });

            Log::info('Magíster eliminado', [
                'nombre' => $nombre,
                'cursos_eliminados' => $cantidadCursos,
                'novedades_actualizadas' => $cantidadNovedades,
                'informes_actualizados' => $cantidadInformes,
                'eventos_actualizados' => $cantidadEventos
            ]);

            // Mensaje detallado
            $detalles = [];
            if ($cantidadCursos > 0) {
                $detalles[] = "$cantidadCursos curso(s) eliminado(s)";
            }
            if ($cantidadNovedades > 0) {
                $detalles[] = "$cantidadNovedades novedad(es) actualizada(s)";
            }
            if ($cantidadInformes > 0) {
                $detalles[] = "$cantidadInformes informe(s) actualizado(s)";
            }
            if ($cantidadEventos > 0) {
                $detalles[] = "$cantidadEventos evento(s) actualizado(s)";
            }

            $mensaje = "Programa \"$nombre\" eliminado correctamente.";
            if (!empty($detalles)) {
                $mensaje .= " (" . implode(', ', $detalles) . ")";
            }

            return redirect()
                ->route('magisters.index')
                ->with('success', $mensaje);

        } catch (Exception $e) {
            Log::error('Error al eliminar magíster: ' . $e->getMessage(), [
                'magister_id' => $magister->id ?? null,
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error al eliminar el programa: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalle del magíster
     */
    public function show(Magister $magister)
    {
        try {
            $magister->load(['courses.period', 'courses.clases']);
            $magister->loadCount('courses');

            return view('magisters.show', compact('magister'));

        } catch (Exception $e) {
            Log::error('Error al mostrar detalle del magíster: ' . $e->getMessage());
            return redirect()->route('magisters.index')->with('error', 'Error al cargar los datos del programa.');
        }
    }
}
