<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'rol' => $user->rol,
                    'created_at' => $user->created_at->format('d/m/Y'),
                    'updated_at' => $user->updated_at->format('d/m/Y H:i')
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el perfil del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}