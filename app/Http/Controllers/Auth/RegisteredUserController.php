<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Validation\Rules;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class RegisteredUserController extends Controller
{
    /**
     * Mostrar vista de registro
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Registrar nuevo usuario (solo para directores administrativos)
     */
    public function store(Request $request): RedirectResponse
    {
        // Validación de datos
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'rol'   => ['required', 'in:director_administrativo,decano,director_programa,asistente_programa,docente,técnico,auxiliar,asistente_postgrado'],
            'foto'  => ['nullable', 'image', 'max:2048'],
            'avatar_color' => ['nullable', 'string', 'max:6'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'Debe ser un email válido.',
            'email.unique' => 'Este email ya está registrado.',
            'rol.required' => 'Debe seleccionar un rol.',
            'rol.in' => 'El rol seleccionado no es válido.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.max' => 'La imagen no puede superar los 2MB.',
        ]);

        // Manejar la subida de la foto a Cloudinary
        if ($request->hasFile('foto')) {
            try {
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

        // Limpiar el # del color si viene
        if (isset($validated['avatar_color']) && $validated['avatar_color']) {
            $color = $validated['avatar_color'];
            if (str_starts_with($color, '#')) {
                $validated['avatar_color'] = substr($color, 1);
            }
        }

        $password = Str::random(12); // genera contraseña aleatoria

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($password),
            'rol'       => $validated['rol'],
            'foto'      => $validated['foto'] ?? null,
            'public_id' => $validated['public_id'] ?? null,
            'avatar_color' => $validated['avatar_color'] ?? null,
        ]);

        // Enviar email de bienvenida con credenciales
        try {
            Mail::to($user->email)->send(new WelcomeUserMail($user, $password));
        } catch (Exception $e) {
            Log::warning('No se pudo enviar email de bienvenida: ' . $e->getMessage());
        }

        // Evento de registro
        event(new Registered($user));

        // Log de auditoría
        Log::info('Usuario creado por administrador', [
            'admin_id' => Auth::id(),
            'admin_name' => Auth::user()->name,
            'new_user_id' => $user->id,
            'new_user_email' => $user->email,
            'new_user_rol' => $user->rol,
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', "Usuario '{$user->name}' creado exitosamente. Se ha enviado un correo con las credenciales de acceso.");
    }
}

