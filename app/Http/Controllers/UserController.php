<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::select('id', 'name', 'email', 'rol', 'created_at', 'last_login_at')->get();
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $usuario->id,
            'rol' => 'required|in:administrativo,docente,asistente,director_magister,director_administrativo,auxiliar',
        ]);

        $usuario->update($request->only('name', 'email', 'rol'));

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === Auth::id()) {
            return redirect()->back()->withErrors(['No puedes eliminar tu propio usuario.']);
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
