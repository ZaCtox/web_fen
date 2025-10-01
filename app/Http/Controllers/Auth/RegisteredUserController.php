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
<<<<<<< Updated upstream
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Solo los roles que aparecen en tu <select>
            'rol'      => ['required', 'in:docente,asistente,director_magister,director_administrativo,auxiliar'],
=======
<<<<<<< Updated upstream
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'rol' => ['required', 'in:estudiante,docente,administrativo'],
=======
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            // Solo los roles que aparecen en tu <select>
            'rol' => ['required', 'in:administrador,director_programa,asistente_programa,tecnico,auxiliar,asistente_postgrado'],
>>>>>>> Stashed changes
>>>>>>> Stashed changes
        ]);

        $password = Str::random(12); // genera contraseña aleatoria
        $user = User::create([
<<<<<<< Updated upstream
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol'      => $validated['rol'],
        ]);

        // Importante para verificación de email (si la tienes habilitada)
        event(new Registered($user));
=======
<<<<<<< Updated upstream
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
        ]);

        return back()
            ->with('success', 'Usuario registrado exitosamente.')
            ->withInput([]); // 👈 esto limpia los valores previos del formulario
=======
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'rol' => $validated['rol'],
        ]);

        // Opcional: envíalo por correo al usuario
        Mail::to($user->email)->send(new WelcomeUserMail($user, $password));

        // Importante para verificación de email (si la tienes habilitada)
        event(new Registered($user));

        return redirect()
            ->route('login')
            ->with('success', 'Cuenta creada. Ahora inicia sesión.');
>>>>>>> Stashed changes
    }
>>>>>>> Stashed changes

        return redirect()
            ->route('login')
            ->with('success', 'Cuenta creada. Ahora inicia sesión.');
    }
}
