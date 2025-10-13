<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffRequest;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Cloudinary\Api\Upload\UploadApi;
use Exception;

class StaffController extends Controller
{
    /**
     * Mostrar listado de personal
     */
    public function index(Request $request)
    {
        try {
            $query = Staff::query();

            // Búsqueda por nombre, cargo o email
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('cargo', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Ordenamiento
            $sortBy = $request->get('sort', 'nombre');
            $sortDirection = $request->get('direction', 'asc');
            
            $staff = $query->orderBy($sortBy, $sortDirection)
                ->paginate(15)
                ->withQueryString();

            return view('staff.index', compact('staff'));

        } catch (Exception $e) {
            Log::error('Error al cargar el listado de personal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar el listado de personal.');
        }
    }

    /**
     * Mostrar detalle de un miembro del personal
     */
    public function show(Staff $staff)
    {
        try {
            return view('staff.show', compact('staff'));
        } catch (Exception $e) {
            Log::error('Error al mostrar detalle del personal: ' . $e->getMessage());
            return redirect()->route('staff.index')->with('error', 'Error al cargar los datos del personal.');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            return view('staff.create');
        } catch (Exception $e) {
            Log::error('Error al cargar formulario de creación de personal: ' . $e->getMessage());
            return redirect()->route('staff.index')->with('error', 'Error al cargar el formulario.');
        }
    }

    /**
     * Almacenar nuevo miembro del personal
     */
    public function store(StaffRequest $request)
    {
        try {
            $validated = $request->validated();
            
            // Verificar si ya existe un staff con el mismo email
            if (Staff::where('email', $validated['email'])->exists()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['email' => 'Ya existe un miembro del personal con este correo electrónico.']);
            }

            // Manejar la subida de la foto a Cloudinary
            if ($request->hasFile('foto')) {
                try {
                    $cloudinaryUpload = (new UploadApi)->upload(
                        $request->file('foto')->getRealPath(),
                        ['folder' => 'staff']
                    );
                    $validated['foto'] = $cloudinaryUpload['secure_url'];
                    $validated['public_id'] = $cloudinaryUpload['public_id'];
                } catch (Exception $e) {
                    Log::error('Error al subir foto a Cloudinary: ' . $e->getMessage());
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['foto' => 'Error al subir la foto. Por favor, inténtelo nuevamente.']);
                }
            }

            $staff = Staff::create($validated);

            Log::info('Nuevo personal creado', ['staff_id' => $staff->id, 'nombre' => $staff->nombre]);

            return redirect()
                ->route('staff.index')
                ->with('success', 'Miembro del personal "' . $staff->nombre . '" creado correctamente.');

        } catch (Exception $e) {
            Log::error('Error al crear personal: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el miembro del personal. Por favor, inténtelo nuevamente.');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Staff $staff)
    {
        try {
            return view('staff.edit', compact('staff'));
        } catch (Exception $e) {
            Log::error('Error al cargar formulario de edición de personal: ' . $e->getMessage());
            return redirect()->route('staff.index')->with('error', 'Error al cargar el formulario de edición.');
        }
    }

    /**
     * Actualizar miembro del personal
     */
    public function update(StaffRequest $request, Staff $staff)
    {
        try {
            $validated = $request->validated();
            
            // Verificar si el email ya existe en otro registro
            if (Staff::where('email', $validated['email'])
                     ->where('id', '!=', $staff->id)
                     ->exists()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['email' => 'Ya existe otro miembro del personal con este correo electrónico.']);
            }

            // Manejar la subida de nueva foto a Cloudinary
            if ($request->hasFile('foto')) {
                try {
                    // Eliminar la foto anterior de Cloudinary si existe
                    if ($staff->public_id) {
                        try {
                            (new UploadApi)->destroy($staff->public_id);
                        } catch (Exception $e) {
                            Log::warning('No se pudo eliminar foto anterior de Cloudinary: ' . $e->getMessage());
                        }
                    }
                    
                    // Subir nueva foto a Cloudinary
                    $cloudinaryUpload = (new UploadApi)->upload(
                        $request->file('foto')->getRealPath(),
                        ['folder' => 'staff']
                    );
                    $validated['foto'] = $cloudinaryUpload['secure_url'];
                    $validated['public_id'] = $cloudinaryUpload['public_id'];
                } catch (Exception $e) {
                    Log::error('Error al subir foto a Cloudinary: ' . $e->getMessage());
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['foto' => 'Error al subir la foto. Por favor, inténtelo nuevamente.']);
                }
            }

            $nombreAnterior = $staff->nombre;
            $staff->update($validated);

            Log::info('Personal actualizado', [
                'staff_id' => $staff->id,
                'nombre_anterior' => $nombreAnterior,
                'nombre_nuevo' => $staff->nombre
            ]);

            return redirect()
                ->route('staff.index')
                ->with('success', 'Información de "' . $staff->nombre . '" actualizada correctamente.');

        } catch (Exception $e) {
            Log::error('Error al actualizar personal: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la información. Por favor, inténtelo nuevamente.');
        }
    }

    /**
     * Eliminar miembro del personal
     */
    public function destroy(Staff $staff)
    {
        try {
            $nombre = $staff->nombre;
            
            // Eliminar la foto de Cloudinary si existe
            if ($staff->public_id) {
                try {
                    (new UploadApi)->destroy($staff->public_id);
                } catch (Exception $e) {
                    Log::warning('No se pudo eliminar foto de Cloudinary: ' . $e->getMessage());
                }
            }
            
            $staff->delete();

            Log::info('Personal eliminado', ['nombre' => $nombre]);

            return redirect()
                ->route('staff.index')
                ->with('success', 'Miembro del personal "' . $nombre . '" eliminado correctamente.');

        } catch (Exception $e) {
            Log::error('Error al eliminar personal: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al eliminar el miembro del personal. Es posible que tenga información relacionada.');
        }
    }

    /**
     * Eliminar solo la foto del personal
     */
    public function deleteFoto(Staff $staff)
    {
        try {
            // Eliminar foto de Cloudinary si existe
            if ($staff->public_id) {
                try {
                    (new UploadApi)->destroy($staff->public_id);
                } catch (Exception $e) {
                    Log::warning('No se pudo eliminar foto de Cloudinary: ' . $e->getMessage());
                }
            }

            // Limpiar campos de foto y public_id
            $staff->foto = null;
            $staff->public_id = null;
            $staff->save();

            Log::info('Foto de personal eliminada', ['staff_id' => $staff->id, 'nombre' => $staff->nombre]);

            return redirect()->back()->with('success', 'Foto eliminada correctamente.');

        } catch (Exception $e) {
            Log::error('Error al eliminar foto de personal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar la foto. Por favor, inténtelo nuevamente.');
        }
    }
}
