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
     * Display the user's profile form.
     */
    /**
     * Mostrar información del usuario (perfil) con opciones de actualización.
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('profile.index', compact('user'));
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
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

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
}
