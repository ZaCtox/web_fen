<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use Exception;

class ProgramController extends Controller
{
    /**
     * Mostrar listado de programas
     */
    public function index(Request $request)
    {
        try {
            // Obtener años de ingreso disponibles
            $aniosIngreso = DB::table('periods')
                ->select('anio_ingreso')
                ->distinct()
                ->orderBy('anio_ingreso', 'desc')
                ->pluck('anio_ingreso');
            
            // Año de ingreso seleccionado (default: el más reciente)
            $anioIngresoSeleccionado = $request->get('anio_ingreso', $aniosIngreso->first());
            
            $query = Program::query()
                ->withCount([
                    'courses' => function($q) use ($anioIngresoSeleccionado) {
                        $q->whereHas('period', function($periodQuery) use ($anioIngresoSeleccionado) {
                            $periodQuery->where('anio_ingreso', $anioIngresoSeleccionado);
                        });
                    }
                ])
                ->withSum([
                    'courses' => function($q) use ($anioIngresoSeleccionado) {
                        $q->whereHas('period', function($periodQuery) use ($anioIngresoSeleccionado) {
                            $periodQuery->where('anio_ingreso', $anioIngresoSeleccionado);
                        });
                    }
                ], 'sct');

            // Búsqueda por nombre
            if ($request->filled('q')) {
                $query->where('name', 'like', '%' . $request->q . '%');
            }

            // Filtro por color
            if ($request->filled('color')) {
                $query->where('color', $request->color);
            }

            // Ordenamiento
            $sortBy = $request->get('sort_by', 'orden');
            $sortDirection = $request->get('sort_direction', 'asc');
            
            if ($sortBy === 'courses_count') {
                $query->orderBy('courses_count', $sortDirection);
            } else {
                $query->orderBy($sortBy, $sortDirection);
            }

            $programs = $query->paginate(20);

            return view('programs.index', compact('programs', 'aniosIngreso', 'anioIngresoSeleccionado'));
        } catch (Exception $e) {
            Log::error('Error en ProgramController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar los programas.');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('programs.create');
    }

    /**
     * Almacenar nuevo programa
     */
    public function store(StoreProgramRequest $request)
    {
        try {
            $program = Program::create($request->validated());
            
            return redirect()->route('programs.index')
                ->with('success', 'Programa creado exitosamente.');
        } catch (Exception $e) {
            Log::error('Error en ProgramController@store: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el programa.');
        }
    }

    /**
     * Mostrar programa específico
     */
    public function show(Program $program)
    {
        $program->load(['courses.period', 'courses.classes']);
        return view('programs.show', compact('program'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Program $program)
    {
        return view('programs.edit', compact('program'));
    }

    /**
     * Actualizar programa
     */
    public function update(UpdateProgramRequest $request, Program $program)
    {
        try {
            $program->update($request->validated());
            
            return redirect()->route('programs.index')
                ->with('success', 'Programa actualizado exitosamente.');
        } catch (Exception $e) {
            Log::error('Error en ProgramController@update: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el programa.');
        }
    }

    /**
     * Eliminar programa
     */
    public function destroy(Program $program)
    {
        try {
            // Verificar si tiene cursos asociados
            if ($program->courses()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el programa porque tiene cursos asociados.');
            }

            $program->delete();
            
            return redirect()->route('programs.index')
                ->with('success', 'Programa eliminado exitosamente.');
        } catch (Exception $e) {
            Log::error('Error en ProgramController@destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar el programa.');
        }
    }

    /**
     * Obtener programas para API
     */
    public function apiIndex(Request $request)
    {
        try {
            $query = Program::query();

            // Filtro por año de ingreso
            if ($request->filled('anio_ingreso')) {
                $query->whereHas('courses.period', function($q) use ($request) {
                    $q->where('anio_ingreso', $request->anio_ingreso);
                });
            }

            // Búsqueda
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $programs = $query->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'data' => $programs
            ]);
        } catch (Exception $e) {
            Log::error('Error en ProgramController@apiIndex: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los programas.'
            ], 500);
        }
    }

    /**
     * Obtener programas con conteo de cursos
     */
    public function withCourseCount(Request $request)
    {
        try {
            $anioIngreso = $request->get('anio_ingreso');
            
            $query = Program::withCount([
                'courses' => function($q) use ($anioIngreso) {
                    if ($anioIngreso) {
                        $q->whereHas('period', function($periodQuery) use ($anioIngreso) {
                            $periodQuery->where('anio_ingreso', $anioIngreso);
                        });
                    }
                }
            ]);

            $programs = $query->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'data' => $programs
            ]);
        } catch (Exception $e) {
            Log::error('Error en ProgramController@withCourseCount: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los programas.'
            ], 500);
        }
    }
}
