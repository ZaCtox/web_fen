<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleRequest;
use App\Models\Module;
use App\Models\Program;
use App\Models\Period;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        // Obtener años de ingreso disponibles
        $aniosIngreso = Period::select('anio_ingreso')
            ->distinct()
            ->whereNotNull('anio_ingreso')
            ->orderBy('anio_ingreso', 'desc')
            ->pluck('anio_ingreso');

        // Año de ingreso seleccionado (por defecto el más reciente)
        $anioIngresoSeleccionado = $request->get('anio_ingreso', $aniosIngreso->first());

        // Cargar programas con módulos filtrados por año de ingreso
        $programs = Program::with(['modules' => function($query) use ($anioIngresoSeleccionado) {
                if ($anioIngresoSeleccionado) {
                    $query->whereHas('period', function($q) use ($anioIngresoSeleccionado) {
                        $q->where('anio_ingreso', $anioIngresoSeleccionado);
                    });
                }
            }, 'modules.period'])
            ->orderBy('order')
            ->get();

        return view('modules.index', compact('programs', 'aniosIngreso', 'anioIngresoSeleccionado'));
    }

    public function create(Request $request)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para crear módulos.');
        }
        
        $programs = Program::orderBy('order')->get();
        $selectedProgramId = $request->program_id;
        $periods = Period::orderBy('anio_ingreso', 'desc')->orderBy('anio')->orderBy('numero')->get();

        // Obtener todos los módulos para el selector de prerrequisitos
        $allModules = Module::with(['program', 'period'])
            ->orderBy('name')
            ->get();

        return view('modules.create', compact('programs', 'selectedProgramId', 'periods', 'allModules'));
    }

    public function store(ModuleRequest $request)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para crear módulos.');
        }

        try {
            $module = Module::create($request->validated());
            
            return redirect()->route('modules.index')
                ->with('success', 'Módulo creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el módulo: ' . $e->getMessage());
        }
    }

    public function show(Module $module)
    {
        $module->load(['program', 'period', 'classes.room']);
        return view('modules.show', compact('module'));
    }

    public function edit(Module $module)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para editar módulos.');
        }

        $programs = Program::orderBy('order')->get();
        $periods = Period::orderBy('anio_ingreso', 'desc')->orderBy('anio')->orderBy('numero')->get();
        
        // Obtener todos los módulos para el selector de prerrequisitos
        $allModules = Module::with(['program', 'period'])
            ->where('id', '!=', $module->id) // Excluir el módulo actual
            ->orderBy('name')
            ->get();

        return view('modules.edit', compact('module', 'programs', 'periods', 'allModules'));
    }

    public function update(ModuleRequest $request, Module $module)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para editar módulos.');
        }

        try {
            $module->update($request->validated());
            
            return redirect()->route('modules.index')
                ->with('success', 'Módulo actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el módulo: ' . $e->getMessage());
        }
    }

    public function destroy(Module $module)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para eliminar módulos.');
        }

        try {
            // Verificar si tiene clases asociadas
            if ($module->classes()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el módulo porque tiene clases asociadas.');
            }

            $module->delete();
            
            return redirect()->route('modules.index')
                ->with('success', 'Módulo eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el módulo: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar todos los módulos de un programa
     */
    public function destroyPrograma(Program $program)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para eliminar módulos.');
        }

        try {
            // Verificar si tiene clases asociadas
            $modulesWithClasses = $program->modules()->whereHas('classes')->count();
            
            if ($modulesWithClasses > 0) {
                return redirect()->back()
                    ->with('error', 'No se pueden eliminar los módulos porque algunos tienen clases asociadas.');
            }

            $program->modules()->delete();
            
            return redirect()->route('modules.index')
                ->with('success', 'Módulos del programa eliminados exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar los módulos: ' . $e->getMessage());
        }
    }

    /**
     * API: Obtener módulos públicos
     */
    public function publicIndex(Request $request)
    {
        try {
            $query = Module::with(['program', 'period']);

            // Filtro por programa
            if ($request->filled('program_id')) {
                $query->where('program_id', $request->program_id);
            }

            // Filtro por año de ingreso
            if ($request->filled('anio_ingreso')) {
                $query->whereHas('period', function($q) use ($request) {
                    $q->where('anio_ingreso', $request->anio_ingreso);
                });
            }

            // Búsqueda
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $modules = $query->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'data' => $modules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los módulos.'
            ], 500);
        }
    }

    /**
     * API: Obtener módulos por programa
     */
    public function publicModulesByProgram(Program $program)
    {
        try {
            $modules = $program->modules()
                ->with(['period'])
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $modules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los módulos del programa.'
            ], 500);
        }
    }

    /**
     * API: Obtener módulos por programa con paginación
     */
    public function publicModulesByProgramPaginated(Program $program, Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            
            $modules = $program->modules()
                ->with(['period'])
                ->orderBy('name')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $modules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los módulos del programa.'
            ], 500);
        }
    }

    /**
     * API: Obtener años disponibles
     */
    public function publicAvailableYears()
    {
        try {
            $years = Period::select('anio_ingreso')
                ->distinct()
                ->whereNotNull('anio_ingreso')
                ->orderBy('anio_ingreso', 'desc')
                ->pluck('anio_ingreso');

            return response()->json([
                'success' => true,
                'data' => $years
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los años disponibles.'
            ], 500);
        }
    }
}
