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
        'avatar_color',
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
     * Genera un avatar con las iniciales
     */
    public function generateAvatarUrl(): string
    {
        $iniciales = $this->iniciales;
        $color = $this->avatar_color ?? $this->getDefaultColor();
        return "https://ui-avatars.com/api/?name={$iniciales}&background={$color}&color=ffffff&size=300&bold=true&font-size=0.4";
    }

    /**
     * Obtiene el color por defecto basado en el ID
     */
    private function getDefaultColor(): string
    {
        // Usar los mismos colores que están en los selectores
        $colores = [
            '005187', // Azul oscuro
            '4d82bc', // Azul medio
            '84b6f4', // Azul claro
            '00acc1', // Cyan
            '66bb6a', // Verde
            'ffa726', // Naranja
            'ef5350', // Rojo
            'ffca28', // Amarillo
            'ab47bc', // Morado
            '78909c', // Gris
        ];
        
        $colorIndex = $this->id % count($colores);
        return $colores[$colorIndex];
    }
}
