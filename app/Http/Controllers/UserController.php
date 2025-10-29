<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Support\Facades\Log;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::select('id', 'name', 'email', 'rol', 'created_at', 'last_login_at', 'foto', 'avatar_color')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function edit(User $usuario)
    {
        
        if ($usuario->id === Auth::id()) {
            return redirect()->route('usuarios.index')
                ->withErrors(['No puedes editar tu propio usuario.']);
        }

        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $usuario->id,
                'rol' => 'required|in:administrador,director_administrativo,director_programa,asistente_programa,docente,técnico,auxiliar,asistente_postgrado',
                'foto' => 'sometimes|nullable|image|max:2048',
                'avatar_color' => 'nullable|string|max:6',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación en UserController@update', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        // Manejar la subida de nueva foto a Cloudinary
        if ($request->hasFile('foto')) {
            try {
                // Eliminar la foto anterior de Cloudinary si existe
                if ($usuario->public_id) {
                    try {
                        (new UploadApi)->destroy($usuario->public_id);
                    } catch (Exception $e) {
                        Log::warning('No se pudo eliminar foto anterior de Cloudinary: ' . $e->getMessage());
                    }
                }
                
                // Subir nueva foto a Cloudinary
                $cloudinaryUpload = (new UploadApi)->upload(
                    $request->file('foto')->getRealPath(),
                    ['folder' => 'usuarios']
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

        $usuario->update($validated);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        
        if ($usuario->id === Auth::id()) {
            return redirect()->back()->withErrors(['No puedes eliminar tu propio usuario.']);
        }

        // Eliminar la foto de Cloudinary si existe
        if ($usuario->public_id) {
            try {
                (new UploadApi)->destroy($usuario->public_id);
            } catch (Exception $e) {
                Log::warning('No se pudo eliminar foto de Cloudinary: ' . $e->getMessage());
            }
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}

