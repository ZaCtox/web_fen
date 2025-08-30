<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('tieneRol')) {
    function tieneRol(array|string $roles): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        $roles = is_array($roles) ? $roles : [$roles];

        return in_array($user->rol, $roles);
    }
}
