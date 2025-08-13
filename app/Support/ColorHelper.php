<?php

namespace App\Support;

class ColorHelper
{
    private static $coloresFijos = [
        'Economía' => '#3b82f6',
        'Dirección y Planificación Tributaria' => '#ef4444',
        'Gestión de Sistemas de Salud' => '#10b981',
        'Gestión y Políticas Públicas' => '#f97316',
    ];

    private static $coloresAleatorios = ['#f472b6', '#a78bfa', '#06b6d4', '#4ade80', '#facc15', '#f87171'];

    public static function obtenerColor(string $nombreMagister): string
    {
        if (isset(self::$coloresFijos[$nombreMagister])) {
            return self::$coloresFijos[$nombreMagister];
        }

        $hash = crc32($nombreMagister);
        return self::$coloresAleatorios[$hash % count(self::$coloresAleatorios)];
    }

    public static function obtenerTodos(): array
    {
        return self::$coloresFijos;
    }
}
