<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $rol = Auth::user()->rol;

        if ($rol !== 'administrativo') {
            abort(403, 'Acceso no autorizado.');
        }

        $usuarios = User::select('id','name', 'email', 'rol')->get();

        return view('usuarios.index', compact('usuarios', 'rol'));
    }
    public function edit(User $user)
    {
        $rol = Auth::user()->rol;

        if ($rol !== 'administrativo') {
            abort(403);
        }

        return view('usuarios.edit', compact('user', 'rol'));
    }

    public function update(Request $request, User $user)
    {
        $rol = Auth::user()->rol;

        if ($rol !== 'administrativo') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'rol' => 'required|in:administrativo,docente',
        ]);

        $user->update($request->only('name', 'email', 'rol'));

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $rol = Auth::user()->rol;

        if ($rol !== 'administrativo') {
            abort(403);
        }

        if ($user->id === Auth::id()) {
            return redirect()->back()->withErrors(['No puedes eliminar tu propio usuario.']);
        }

        $user->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
