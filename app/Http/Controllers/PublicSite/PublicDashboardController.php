<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Emergency;
use App\Models\Novedad;
use App\Models\Room;
use App\Models\Magister;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class PublicDashboardController extends Controller
{
    /**
     * Mostrar dashboard público con novedades
     */
    public function index(Request $request)
    {
        try {
            // Redirige a dashboard si está logueado
            if (Auth::check()) {
                return redirect()->route('dashboard');
            }

            // Emergencia más reciente
            $emergency = Emergency::latest()->first();

            // Novedades públicas activas (ordenadas por urgencia y fecha)
            $novedades = Novedad::where('visible_publico', true)
                ->activas()
                ->orderBy('es_urgente', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();

            // Novedades urgentes para destacar
            $novedadesUrgentes = Novedad::where('visible_publico', true)
                ->activas()
                ->urgentes()
                ->latest()
                ->limit(3)
                ->get();

            // Datos de salas y programas
            $rooms = Room::orderBy('name')->get();
            $magisters = Magister::with(['courses.period'])->orderBy('nombre')->get();

            return view('public.dashboard', compact(
                'rooms',
                'magisters',
                'emergency',
                'novedades',
                'novedadesUrgentes'
            ));

        } catch (Exception $e) {
            Log::error('Error al cargar dashboard público: ' . $e->getMessage());
            
            // En caso de error, mostrar vista básica
            return view('public.dashboard', [
                'rooms' => collect(),
                'magisters' => collect(),
                'emergency' => null,
                'novedades' => collect(),
                'novedadesUrgentes' => collect()
            ]);
        }
    }

    /**
     * Mostrar todas las novedades públicas
     */
    public function novedades(Request $request)
    {
        try {
            $query = Novedad::where('visible_publico', true)->activas();

            // Filtro por búsqueda de texto
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('titulo', 'like', "%{$search}%")
                      ->orWhere('contenido', 'like', "%{$search}%");
                });
            }

            // Filtro por tipo
            if ($request->filled('tipo')) {
                $query->porTipo($request->tipo);
            }

            // Filtro por magíster
            if ($request->filled('magister_id')) {
                $query->where('magister_id', $request->magister_id);
            }

            $novedades = $query->orderBy('es_urgente', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(12)
                ->withQueryString();

            $magisters = Magister::orderBy('nombre')->get();
            
            $tipos = [
                'academica' => 'Académica',
                'evento' => 'Evento',
                'admision' => 'Admisión',
                'institucional' => 'Institucional',
                'servicio' => 'Servicio'
            ];

            return view('public.novedades', compact('novedades', 'magisters', 'tipos'));

        } catch (Exception $e) {
            Log::error('Error al cargar novedades públicas: ' . $e->getMessage());
            return redirect()->route('public.dashboard')->with('error', 'Error al cargar las novedades.');
        }
    }

    /**
     * Mostrar detalle de una novedad
     */
    public function novedadDetalle(Novedad $novedad)
    {
        try {
            // Verificar que sea pública
            if (!$novedad->visible_publico) {
                abort(404);
            }

            // Verificar que no esté expirada
            if ($novedad->estaExpirada()) {
                return redirect()->route('public.novedades')
                    ->with('info', 'Esta novedad ya no está disponible.');
            }

            $novedad->load('magister', 'user');

            // Novedades relacionadas
            $relacionadas = Novedad::where('visible_publico', true)
                ->activas()
                ->where('id', '!=', $novedad->id)
                ->when($novedad->magister_id, fn($q) => $q->where('magister_id', $novedad->magister_id))
                ->latest()
                ->limit(3)
                ->get();

            return view('public.novedad-detalle', compact('novedad', 'relacionadas'));

        } catch (Exception $e) {
            Log::error('Error al cargar detalle de novedad: ' . $e->getMessage());
            return redirect()->route('public.novedades')->with('error', 'Error al cargar la novedad.');
        }
    }
}

