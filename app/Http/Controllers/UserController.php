<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $this->authorizeAccess();

        $usuarios = User::select('id', 'name', 'email', 'rol')->get();
        $rol = Auth::user()->rol;

        return view('usuarios.index', compact('usuarios', 'rol'));
    }

public function edit(User $user)
{
    $this->authorizeAccess();

    if ($user->id === Auth::id()) {
        return redirect()->route('usuarios.index')->withErrors(['No puedes editar tu propio usuario.']);
    }

    $rol = Auth::user()->rol;

    return view('usuarios.edit', compact('user', 'rol'));
}


    public function update(Request $request, User $user)
    {
        $this->authorizeAccess();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'rol' => [
                'required',
                'in:administrativo,docente,asistente,director_magister,director_administrativo,auxiliar',
            ],
        ]);

        $user->update($request->only('name', 'email', 'rol'));

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $this->authorizeAccess();

        if ($user->id === Auth::id()) {
            return redirect()->back()->withErrors(['No puedes eliminar tu propio usuario.']);
        }

        $user->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }

    private function authorizeAccess(): void
    {
        if (!tieneRol(['administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }
    }
}
