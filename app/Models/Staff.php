<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'nombre', 'cargo', 'telefono','anexo', 'correo', 'email', 'foto', 'public_id',
    ];

    /**
     * Obtiene la URL de la foto de perfil o genera un avatar con iniciales
     */
    public function getFotoPerfilAttribute(): string
    {
        if ($this->foto) {
            // Si tiene foto guardada en Cloudinary, devolver la URL directamente
            return $this->foto;
        }
        
        // Si no tiene foto, generar avatar con iniciales
        return $this->generateAvatarUrl();
    }

    /**
     * Obtiene las iniciales del nombre (máximo 2 letras)
     */
    public function getInicialesAttribute(): string
    {
        $palabras = explode(' ', trim($this->nombre));
        
        if (count($palabras) >= 2) {
            // Primera letra del nombre + primera letra del apellido
            return strtoupper(substr($palabras[0], 0, 1) . substr($palabras[1], 0, 1));
        }
        
        // Solo primera y segunda letra del nombre
        return strtoupper(substr($this->nombre, 0, 2));
    }

    /**
     * Genera un avatar con las iniciales usando UI Avatars
     */
    private function generateAvatarUrl(): string
    {
        $iniciales = $this->iniciales;
        $colores = [
            ['bg' => '005187', 'color' => 'ffffff'], // Azul oscuro
            ['bg' => '4d82bc', 'color' => 'ffffff'], // Azul medio
            ['bg' => '84b6f4', 'color' => '000000'], // Azul claro
            ['bg' => 'ffa726', 'color' => '000000'], // Naranja
            ['bg' => '66bb6a', 'color' => 'ffffff'], // Verde
            ['bg' => 'ab47bc', 'color' => 'ffffff'], // Morado
        ];
        
        // Seleccionar color basado en el ID para consistencia
        $colorIndex = $this->id % count($colores);
        $color = $colores[$colorIndex];
        
        return "https://ui-avatars.com/api/?name={$iniciales}&background={$color['bg']}&color={$color['color']}&size=300&bold=true&font-size=0.4";
    }
}
