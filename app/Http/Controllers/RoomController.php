<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Period;
use App\Models\Course;
use App\Models\Magister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreRoomRequest;
use Exception;

class RoomController extends Controller
{
    /**
     * Mostrar listado de salas
     */
    public function index(Request $request)
    {
        try {
            $query = Room::query();

            // Filtro por ubicación
            if ($request->filled('ubicacion')) {
                $query->where('location', 'like', '%' . $request->ubicacion . '%');
            }

            // Filtro por capacidad mínima
            if ($request->filled('capacidad')) {
                $query->where('capacity', '>=', $request->capacidad);
            }

            // Búsqueda por nombre
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Ordenamiento
            $sortBy = $request->get('sort', 'name');
            $sortDirection = $request->get('direction', 'asc');

            $rooms = $query->orderBy($sortBy, $sortDirection)
                ->withCount('clases')
                ->paginate(10)
                ->withQueryString();

            return view('rooms.index', compact('rooms'));

        } catch (Exception $e) {
            Log::error('Error al cargar listado de salas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar el listado de salas.');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
                try {
            $periodos = Period::orderByDesc('anio')->orderBy('numero')->get();
            $cursos = Course::with('magister')->orderBy('magister_id')->orderBy('nombre')->get();

            return view('rooms.create', compact('periodos', 'cursos'));

        } catch (Exception $e) {
            Log::error('Error al cargar formulario de creación de sala: ' . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', 'Error al cargar el formulario.');
        }
    }

    /**
     * Almacenar nueva sala
     */
    public function store(StoreRoomRequest $request)
    {
                try {
            $data = $request->validated();

            // Verificar si ya existe una sala con el mismo nombre
            if (Room::where('name', $data['name'])->exists()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['name' => 'Ya existe una sala con este nombre.']);
            }

            // Procesar campos booleanos
            foreach ($this->booleanFields() as $campo) {
                $data[$campo] = $request->has($campo);
            }

            $room = Room::create($data);

            Log::info('Nueva sala creada', ['room_id' => $room->id, 'name' => $room->name]);

            return redirect()
                ->route('rooms.index')
                ->with('success', 'Sala "' . $room->name . '" creada correctamente.');

        } catch (Exception $e) {
            Log::error('Error al crear sala: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la sala. Por favor, inténtelo nuevamente.');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Room $room)
    {
                try {
            $room->loadCount('clases');
            return view('rooms.edit', compact('room'));

        } catch (Exception $e) {
            Log::error('Error al cargar formulario de edición de sala: ' . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', 'Error al cargar el formulario de edición.');
        }
    }

    /**
     * Actualizar sala existente
     */
    public function update(StoreRoomRequest $request, Room $room)
    {
                try {
            $data = $request->validated();

            // Verificar si el nombre ya existe en otra sala
            if (Room::where('name', $data['name'])
                     ->where('id', '!=', $room->id)
                     ->exists()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['name' => 'Ya existe otra sala con este nombre.']);
            }

            // Procesar campos booleanos
            foreach ($this->booleanFields() as $campo) {
                $data[$campo] = $request->has($campo);
            }

            $nombreAnterior = $room->name;
            $room->update($data);

            Log::info('Sala actualizada', [
                'room_id' => $room->id,
                'nombre_anterior' => $nombreAnterior,
                'nombre_nuevo' => $room->name
            ]);

            return redirect()
                ->route('rooms.index')
                ->with('success', 'Sala "' . $room->name . '" actualizada correctamente.');

        } catch (Exception $e) {
            Log::error('Error al actualizar sala: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la sala. Por favor, inténtelo nuevamente.');
        }
    }

    /**
     * Eliminar sala
     */
    public function destroy(Room $room)
    {
                try {
            $nombre = $room->name;
            $cantidadClases = $room->clases()->count();
            $cantidadIncidencias = $room->incidents()->count();
            $cantidadEventos = $room->events()->count();
            $cantidadReportEntries = $room->reportEntries()->count();

            // Verificar si tiene registros asociados que NO pueden ser NULL
            if ($cantidadClases > 0) {
                return redirect()->back()
                    ->with('error', "No se puede eliminar la sala \"$nombre\" porque tiene $cantidadClases clase(s) asociada(s). Primero elimina o reasigna las clases.");
            }

            if ($cantidadIncidencias > 0) {
                return redirect()->back()
                    ->with('error', "No se puede eliminar la sala \"$nombre\" porque tiene $cantidadIncidencias incidencia(s) registrada(s). Primero elimina o reasigna las incidencias.");
            }

            // Informar sobre registros que se actualizarán a NULL
            $detalles = [];
            if ($cantidadEventos > 0) {
                $detalles[] = "$cantidadEventos evento(s)";
            }
            if ($cantidadReportEntries > 0) {
                $detalles[] = "$cantidadReportEntries entrada(s) de reporte diario";
            }

            $room->delete();

            Log::info('Sala eliminada', [
                'nombre' => $nombre,
                'eventos_actualizados' => $cantidadEventos,
                'report_entries_actualizadas' => $cantidadReportEntries
            ]);

            $mensaje = "Sala \"$nombre\" eliminada correctamente.";
            if (!empty($detalles)) {
                $mensaje .= " Se actualizaron a NULL: " . implode(', ', $detalles) . ".";
            }

            return redirect()
                ->route('rooms.index')
                ->with('success', $mensaje);

        } catch (Exception $e) {
            Log::error('Error al eliminar sala: ' . $e->getMessage(), [
                'room_id' => $room->id ?? null,
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error al eliminar la sala: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalle de la sala
     */
    public function show(Room $room)
    {
        try {
            $room->loadCount(['clases', 'incidents']);
            
            // Obtener sesiones con sus relaciones
            $sesiones = \App\Models\ClaseSesion::where('room_id', $room->id)
                ->with(['clase.course.magister', 'clase.period'])
                ->orderBy('fecha', 'asc')
                ->get();

            // Filtros dinámicos para vista
            $magisters = Magister::orderBy('nombre')->get();
            
            // Obtener días únicos desde las sesiones
            $dias = \App\Models\ClaseSesion::where('room_id', $room->id)
                ->distinct()
                ->pluck('dia')
                ->filter()
                ->sort()
                ->values();
            
            $trimestres = Period::orderBy('anio')->orderBy('numero')->get();

            return view('rooms.show', compact('room', 'sesiones', 'magisters', 'dias', 'trimestres'));

        } catch (Exception $e) {
            Log::error('Error al mostrar detalle de sala: ' . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', 'Error al cargar los datos de la sala.');
        }
    }

    /**
     * Obtener campos booleanos de la sala
     */
    private function booleanFields(): array
    {
        return [
            'calefaccion',
            'energia_electrica',
            'existe_aseo',
            'plumones',
            'borrador',
            'pizarra_limpia',
            'computador_funcional',
            'cables_computador',
            'control_remoto_camara',
            'televisor_funcional',
        ];
    }
}

