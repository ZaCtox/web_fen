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
     * Registrar nuevo usuario
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            // Roles permitidos según tu formulario
            'rol'   => ['required', 'in:administrador,director_programa,asistente_programa,técnico,auxiliar,asistente_postgrado'],
        ]);

        $password = Str::random(12); // genera contraseña aleatoria

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($password),
            'rol'      => $validated['rol'],
        ]);

        // Opcional: enviar contraseña por correo al usuario
        Mail::to($user->email)->send(new WelcomeUserMail($user, $password));

        // Evento de registro para verificación de email
        event(new Registered($user));

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente. Se ha enviado un correo con las credenciales de acceso.');
    }
}
