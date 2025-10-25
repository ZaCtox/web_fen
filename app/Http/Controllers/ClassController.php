<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassRequest;
use App\Http\Requests\UpdateClassRequest;
use App\Models\Class as ClassModel;
use App\Models\Course;
use App\Models\Program;
use App\Models\Period;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClassController extends Controller
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

        $query = ClassModel::with(['module.program', 'period', 'room', 'sesiones']);

        // Filtros de periodo (anio_ingreso, año, trimestre) - todos juntos en una sola consulta
        $query->whereHas('period', function($q) use ($anioIngresoSeleccionado, $request) {
            if ($anioIngresoSeleccionado) {
                $q->where('anio_ingreso', $anioIngresoSeleccionado);
            }
            if ($request->filled('anio')) {
                $q->where('anio', $request->anio);
            }
            if ($request->filled('trimestre')) {
                $q->where('numero', $request->trimestre);
            }
        });

        if ($request->filled('program')) {
            $query->whereHas('module.program', fn($q) => $q->where('name', $request->program));
        }

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        if ($request->filled('modality')) {
            $query->where('modality', $request->modality);
        }

        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }

        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->where('date', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->where('date', '<=', $request->fecha_hasta);
        }

        $classes = $query->orderBy('date', 'desc')->paginate(20);

        // Datos para filtros
        $programs = Program::orderBy('name')->pluck('name', 'id');
        $rooms = Room::orderBy('name')->pluck('name', 'id');
        $periods = Period::where('anio_ingreso', $anioIngresoSeleccionado)
            ->orderBy('anio', 'desc')
            ->orderBy('numero', 'desc')
            ->get();

        return view('classes.index', compact('classes', 'programs', 'rooms', 'periods', 'aniosIngreso', 'anioIngresoSeleccionado'));
    }

    public function create()
    {
        $modules = Module::with('program')->orderBy('name')->get();
        $periods = Period::orderBy('anio', 'desc')->orderBy('numero', 'desc')->get();
        $rooms = Room::orderBy('name')->get();
        
        return view('classes.create', compact('modules', 'periods', 'rooms'));
    }

    public function store(StoreClassRequest $request)
    {
        $class = ClassModel::create($request->validated());
        
        return redirect()->route('classes.index')
            ->with('success', 'Clase creada exitosamente.');
    }

    public function show(ClassModel $class)
    {
        $class->load(['module.program', 'period', 'room', 'sesiones']);
        
        return view('classes.show', compact('class'));
    }

    public function edit(ClassModel $class)
    {
        $modules = Module::with('program')->orderBy('name')->get();
        $periods = Period::orderBy('anio', 'desc')->orderBy('numero', 'desc')->get();
        $rooms = Room::orderBy('name')->get();
        
        return view('classes.edit', compact('class', 'modules', 'periods', 'rooms'));
    }

    public function update(UpdateClassRequest $request, ClassModel $class)
    {
        $class->update($request->validated());
        
        return redirect()->route('classes.index')
            ->with('success', 'Clase actualizada exitosamente.');
    }

    public function destroy(ClassModel $class)
    {
        $class->delete();
        
        return redirect()->route('classes.index')
            ->with('success', 'Clase eliminada exitosamente.');
    }

    // Métodos adicionales específicos del controlador
    public function exportar(Request $request)
    {
        // Lógica de exportación
        $query = ClassModel::with(['module.program', 'period', 'room']);
        
        // Aplicar filtros similares al index
        if ($request->filled('anio_ingreso')) {
            $query->whereHas('period', function($q) use ($request) {
                $q->where('anio_ingreso', $request->anio_ingreso);
            });
        }
        
        $classes = $query->get();
        
        $pdf = Pdf::loadView('classes.export', compact('classes'));
        return $pdf->download('clases-' . now()->format('Y-m-d') . '.pdf');
    }

    public function disponibilidad(Request $request)
    {
        // Lógica para verificar disponibilidad de salas
        $date = $request->get('date');
        $startTime = $request->get('start_time');
        $endTime = $request->get('end_time');
        
        // Implementar lógica de disponibilidad
        return response()->json(['available' => true]);
    }

    public function horariosDisponibles(Request $request)
    {
        // Lógica para obtener horarios disponibles
        return response()->json(['horarios' => []]);
    }

    public function salasDisponibles(Request $request)
    {
        // Lógica para obtener salas disponibles
        $rooms = Room::where('available', true)->get();
        return response()->json($rooms);
    }
}
