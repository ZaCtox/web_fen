<?php

namespace App\Http\Controllers;

use App\Models\MallaCurricular;
use App\Models\Magister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class MallaCurricularController extends Controller
{
    /**
     * Mostrar listado de mallas curriculares
     */
    public function index(Request $request)
    {
        try {
            $query = MallaCurricular::with('magister')->withCount('courses');

            // Filtro por magíster
            if ($request->filled('magister_id')) {
                $query->where('magister_id', $request->magister_id);
            }

            // Filtro por estado
            if ($request->filled('activa')) {
                $query->where('activa', $request->activa);
            }

            // Filtro por año
            if ($request->filled('año')) {
                $año = $request->año;
                $query->where('año_inicio', '<=', $año)
                      ->where(function($q) use ($año) {
                          $q->whereNull('año_fin')
                            ->orWhere('año_fin', '>=', $año);
                      });
            }

            $mallas = $query->orderBy('año_inicio', 'desc')->paginate(10);
            $magisters = Magister::orderBy('orden')->get();

            return view('mallas-curriculares.index', compact('mallas', 'magisters'));

        } catch (Exception $e) {
            Log::error('Error al cargar mallas curriculares: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar las mallas curriculares.');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            $magisters = Magister::orderBy('orden')->get();
            return view('mallas-curriculares.create', compact('magisters'));
        } catch (Exception $e) {
            Log::error('Error al cargar formulario de creación: ' . $e->getMessage());
            return redirect()->route('mallas-curriculares.index')->with('error', 'Error al cargar el formulario.');
        }
    }

    /**
     * Almacenar nueva malla curricular
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'magister_id' => 'required|exists:magisters,id',
                'nombre' => 'required|string|max:255',
                'codigo' => 'required|string|max:50|unique:malla_curriculars,codigo',
                'año_inicio' => 'required|integer|min:2020|max:2100',
                'año_fin' => 'nullable|integer|min:2020|max:2100|gte:año_inicio',
                'activa' => 'boolean',
                'descripcion' => 'nullable|string'
            ]);

            $validated['activa'] = $request->has('activa');

            $malla = MallaCurricular::create($validated);

            Log::info('Nueva malla curricular creada', ['malla_id' => $malla->id, 'codigo' => $malla->codigo]);

            return redirect()
                ->route('mallas-curriculares.index')
                ->with('success', 'Malla curricular "' . $malla->nombre . '" creada correctamente.');

        } catch (Exception $e) {
            Log::error('Error al crear malla curricular: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la malla curricular.');
        }
    }

    /**
     * Mostrar detalle de la malla curricular
     */
    public function show(MallaCurricular $mallaCurricular)
    {
        try {
            $mallaCurricular->load(['magister', 'courses.period', 'courses.clases']);
            $mallaCurricular->loadCount('courses');

            return view('mallas-curriculares.show', compact('mallaCurricular'));

        } catch (Exception $e) {
            Log::error('Error al mostrar malla curricular: ' . $e->getMessage());
            return redirect()->route('mallas-curriculares.index')->with('error', 'Error al cargar la malla curricular.');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(MallaCurricular $mallaCurricular)
    {
        try {
            $magisters = Magister::orderBy('orden')->get();
            $mallaCurricular->loadCount('courses');
            
            return view('mallas-curriculares.edit', compact('mallaCurricular', 'magisters'));
        } catch (Exception $e) {
            Log::error('Error al cargar formulario de edición: ' . $e->getMessage());
            return redirect()->route('mallas-curriculares.index')->with('error', 'Error al cargar el formulario de edición.');
        }
    }

    /**
     * Actualizar malla curricular
     */
    public function update(Request $request, MallaCurricular $mallaCurricular)
    {
        try {
            $validated = $request->validate([
                'magister_id' => 'required|exists:magisters,id',
                'nombre' => 'required|string|max:255',
                'codigo' => 'required|string|max:50|unique:malla_curriculars,codigo,' . $mallaCurricular->id,
                'año_inicio' => 'required|integer|min:2020|max:2100',
                'año_fin' => 'nullable|integer|min:2020|max:2100|gte:año_inicio',
                'activa' => 'boolean',
                'descripcion' => 'nullable|string'
            ]);

            $validated['activa'] = $request->has('activa');

            $mallaCurricular->update($validated);

            Log::info('Malla curricular actualizada', ['malla_id' => $mallaCurricular->id]);

            return redirect()
                ->route('mallas-curriculares.index')
                ->with('success', 'Malla curricular actualizada correctamente.');

        } catch (Exception $e) {
            Log::error('Error al actualizar malla curricular: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la malla curricular.');
        }
    }

    /**
     * Eliminar malla curricular
     */
    public function destroy(MallaCurricular $mallaCurricular)
    {
        try {
            $cantidadCursos = $mallaCurricular->courses()->count();
            
            if ($cantidadCursos > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar la malla curricular porque tiene ' . $cantidadCursos . ' curso(s) asociado(s). Primero reasigne o elimine los cursos.');
            }

            $nombre = $mallaCurricular->nombre;
            $mallaCurricular->delete();

            Log::info('Malla curricular eliminada', ['nombre' => $nombre]);

            return redirect()
                ->route('mallas-curriculares.index')
                ->with('success', 'Malla curricular "' . $nombre . '" eliminada correctamente.');

        } catch (Exception $e) {
            Log::error('Error al eliminar malla curricular: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al eliminar la malla curricular.');
        }
    }

    /**
     * Cambiar estado activo/inactivo
     */
    public function toggleEstado(MallaCurricular $mallaCurricular)
    {
        try {
            $mallaCurricular->activa = !$mallaCurricular->activa;
            $mallaCurricular->save();

            $estado = $mallaCurricular->activa ? 'activada' : 'desactivada';

            return redirect()->back()
                ->with('success', 'Malla curricular ' . $estado . ' correctamente.');

        } catch (Exception $e) {
            Log::error('Error al cambiar estado de malla: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cambiar el estado.');
        }
    }
}
