<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class AnnouncementController extends Controller
{
    /**
     * Mostrar listado de anuncios
     */
    public function index(Request $request)
    {
        try {
            $query = Announcement::with(['program', 'user']);

            // Filtro por tipo
            if ($request->filled('tipo')) {
                $query->porTipo($request->tipo);
            }

            // Filtro por estado (activa/expirada)
            if ($request->filled('estado')) {
                if ($request->estado === 'activa') {
                    $query->activas();
                } elseif ($request->estado === 'expirada') {
                    $query->where('expiration_date', '<=', now());
                }
            }

            // Filtro por visibilidad
            if ($request->filled('visibilidad')) {
                if ($request->visibilidad === 'publica') {
                    $query->where('is_public', true);
                } elseif ($request->visibilidad === 'privada') {
                    $query->where('is_public', false);
                }
            }

            // Búsqueda
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('content', 'like', '%' . $request->search . '%');
                });
            }

            // Filtro por programa
            if ($request->filled('program_id')) {
                $query->where('program_id', $request->program_id);
            }

            // Filtro por urgente
            if ($request->filled('urgente')) {
                $query->where('is_urgent', $request->urgente === '1');
            }

            // Ordenamiento
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            
            $query->orderBy($sortBy, $sortDirection);

            $announcements = $query->paginate(20);
            $programs = Program::orderBy('name')->get();

            return view('announcements.index', compact('announcements', 'programs'));
        } catch (Exception $e) {
            Log::error('Error en AnnouncementController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar los anuncios.');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $programs = Program::orderBy('name')->get();
        return view('announcements.create', compact('programs'));
    }

    /**
     * Almacenar nuevo anuncio
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'announcement_type' => 'required|string',
                'program_id' => 'nullable|exists:programs,id',
                'is_public' => 'boolean',
                'is_urgent' => 'boolean',
                'expiration_date' => 'nullable|date|after:now',
                'icon' => 'nullable|string',
                'color' => 'nullable|string',
                'visible_roles' => 'nullable|array',
            ]);

            $announcement = Announcement::create([
                'title' => $request->title,
                'content' => $request->content,
                'announcement_type' => $request->announcement_type,
                'program_id' => $request->program_id,
                'user_id' => Auth::id(),
                'is_public' => $request->boolean('is_public', true),
                'is_urgent' => $request->boolean('is_urgent', false),
                'expiration_date' => $request->expiration_date,
                'icon' => $request->icon ?? '📰',
                'color' => $request->color ?? 'blue',
                'visible_roles' => $request->visible_roles,
            ]);

            return redirect()->route('announcements.index')
                ->with('success', 'Anuncio creado exitosamente.');
        } catch (Exception $e) {
            Log::error('Error en AnnouncementController@store: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el anuncio.');
        }
    }

    /**
     * Mostrar anuncio específico
     */
    public function show(Announcement $announcement)
    {
        $announcement->load(['program', 'user']);
        return view('announcements.show', compact('announcement'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Announcement $announcement)
    {
        $programs = Program::orderBy('name')->get();
        return view('announcements.edit', compact('announcement', 'programs'));
    }

    /**
     * Actualizar anuncio
     */
    public function update(Request $request, Announcement $announcement)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'announcement_type' => 'required|string',
                'program_id' => 'nullable|exists:programs,id',
                'is_public' => 'boolean',
                'is_urgent' => 'boolean',
                'expiration_date' => 'nullable|date|after:now',
                'icon' => 'nullable|string',
                'color' => 'nullable|string',
                'visible_roles' => 'nullable|array',
            ]);

            $announcement->update([
                'title' => $request->title,
                'content' => $request->content,
                'announcement_type' => $request->announcement_type,
                'program_id' => $request->program_id,
                'is_public' => $request->boolean('is_public', true),
                'is_urgent' => $request->boolean('is_urgent', false),
                'expiration_date' => $request->expiration_date,
                'icon' => $request->icon ?? '📰',
                'color' => $request->color ?? 'blue',
                'visible_roles' => $request->visible_roles,
            ]);

            return redirect()->route('announcements.index')
                ->with('success', 'Anuncio actualizado exitosamente.');
        } catch (Exception $e) {
            Log::error('Error en AnnouncementController@update: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el anuncio.');
        }
    }

    /**
     * Eliminar anuncio
     */
    public function destroy(Announcement $announcement)
    {
        try {
            $announcement->delete();
            return redirect()->route('announcements.index')
                ->with('success', 'Anuncio eliminado exitosamente.');
        } catch (Exception $e) {
            Log::error('Error en AnnouncementController@destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar el anuncio.');
        }
    }

    /**
     * Duplicar anuncio
     */
    public function duplicate(Announcement $announcement)
    {
        try {
            $newAnnouncement = $announcement->replicate();
            $newAnnouncement->title = $announcement->title . ' (Copia)';
            $newAnnouncement->user_id = Auth::id();
            $newAnnouncement->created_at = now();
            $newAnnouncement->updated_at = now();
            $newAnnouncement->save();

            return redirect()->route('announcements.index')
                ->with('success', 'Anuncio duplicado exitosamente.');
        } catch (Exception $e) {
            Log::error('Error en AnnouncementController@duplicate: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al duplicar el anuncio.');
        }
    }
}
