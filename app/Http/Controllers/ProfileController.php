<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Support\Facades\Log;
use Exception;

class ProfileController extends Controller
{
    /**
     * Mostrar información del usuario (perfil) con opciones de actualización.
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('profile.index', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.index')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Actualizar foto de perfil del usuario
     */
    public function updateFoto(Request $request): RedirectResponse
    {
        $request->validate([
            'foto' => 'required|image|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('foto')) {
            try {
                // Eliminar la foto anterior de Cloudinary si existe
                if ($user->public_id) {
                    try {
                        (new UploadApi)->destroy($user->public_id);
                    } catch (Exception $e) {
                        Log::warning('No se pudo eliminar foto anterior de Cloudinary: ' . $e->getMessage());
                    }
                }
                
                // Subir nueva foto a Cloudinary
                $cloudinaryUpload = (new UploadApi)->upload(
                    $request->file('foto')->getRealPath(),
                    ['folder' => 'usuarios']
                );
                
                $user->foto = $cloudinaryUpload['secure_url'];
                $user->public_id = $cloudinaryUpload['public_id'];
                $user->save();

                return Redirect::route('profile.index')
                    ->with('foto-updated', 'Foto de perfil actualizada correctamente.');
                    
            } catch (Exception $e) {
                Log::error('Error al subir foto a Cloudinary: ' . $e->getMessage());
                return Redirect::back()
                    ->withErrors(['foto' => 'Error al subir la foto. Por favor, inténtelo nuevamente.']);
            }
        }

        return Redirect::back()->withErrors(['foto' => 'No se seleccionó ninguna foto.']);
    }

    /**
     * Eliminar foto de perfil del usuario
     */
    public function deleteFoto(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->public_id) {
            try {
                (new UploadApi)->destroy($user->public_id);
            } catch (Exception $e) {
                Log::warning('No se pudo eliminar foto de Cloudinary: ' . $e->getMessage());
            }
        }

        $user->foto = null;
        $user->public_id = null;
        $user->save();

        return Redirect::route('profile.index')
            ->with('foto-updated', 'Foto de perfil eliminada correctamente.');
    }

    /**
     * Actualizar configuración de avatar
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar_color' => 'nullable|string|max:7',
        ]);

        $user = Auth::user();
        
        // Limpiar el # del color si viene
        $color = $request->avatar_color;
        if ($color && str_starts_with($color, '#')) {
            $color = substr($color, 1);
        }
        
        $user->avatar_color = $color ?: null;
        $user->save();

        return Redirect::route('profile.index')
            ->with('avatar-updated', 'Color de avatar actualizado correctamente.');
    }

    /**
     * Obtener el avatar del usuario autenticado
     */
    public function getAvatar(Request $request)
    {
        $user = Auth::user();
        
        if ($user->foto) {
            return redirect($user->foto);
        }
        
        // Si se pasa un color en la URL, usarlo temporalmente para el preview
        if ($request->has('color')) {
            $tempColor = $request->input('color');
            $user->avatar_color = $tempColor;
        }
        
        // Generar el avatar
        $avatarUrl = $user->generateAvatarUrl();
        return redirect($avatarUrl);
    }
}
