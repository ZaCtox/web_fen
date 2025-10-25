<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Renombrar tablas principales
        if (Schema::hasTable('clases')) {
            Schema::rename('clases', 'classes');
        }
        
        if (Schema::hasTable('novedades')) {
            Schema::rename('novedades', 'announcements');
        }
        
        if (Schema::hasTable('magisters')) {
            Schema::rename('magisters', 'programs');
        }

        // 2. Renombrar columnas en tabla 'classes' (antes 'clases')
        if (Schema::hasTable('classes')) {
            Schema::table('classes', function (Blueprint $table) {
                // Renombrar columnas específicas
                if (Schema::hasColumn('classes', 'dia')) {
                    $table->renameColumn('dia', 'day');
                }
                if (Schema::hasColumn('classes', 'hora_inicio')) {
                    $table->renameColumn('hora_inicio', 'start_time');
                }
                if (Schema::hasColumn('classes', 'hora_fin')) {
                    $table->renameColumn('hora_fin', 'end_time');
                }
                if (Schema::hasColumn('classes', 'url_zoom')) {
                    $table->renameColumn('url_zoom', 'zoom_url');
                }
            });
        }

        // 3. Renombrar columnas en tabla 'announcements' (antes 'novedades')
        if (Schema::hasTable('announcements')) {
            Schema::table('announcements', function (Blueprint $table) {
                if (Schema::hasColumn('announcements', 'titulo')) {
                    $table->renameColumn('titulo', 'title');
                }
                if (Schema::hasColumn('announcements', 'contenido')) {
                    $table->renameColumn('contenido', 'content');
                }
                if (Schema::hasColumn('announcements', 'tipo_novedad')) {
                    $table->renameColumn('tipo_novedad', 'announcement_type');
                }
                if (Schema::hasColumn('announcements', 'es_urgente')) {
                    $table->renameColumn('es_urgente', 'is_urgent');
                }
                if (Schema::hasColumn('announcements', 'fecha_expiracion')) {
                    $table->renameColumn('fecha_expiracion', 'expiration_date');
                }
                if (Schema::hasColumn('announcements', 'visible_publico')) {
                    $table->renameColumn('visible_publico', 'is_public');
                }
                if (Schema::hasColumn('announcements', 'roles_visibles')) {
                    $table->renameColumn('roles_visibles', 'visible_roles');
                }
            });
        }

        // 4. Renombrar columnas en tabla 'programs' (antes 'magisters')
        if (Schema::hasTable('programs')) {
            Schema::table('programs', function (Blueprint $table) {
                if (Schema::hasColumn('programs', 'nombre')) {
                    $table->renameColumn('nombre', 'name');
                }
            });
        }

        // 5. Renombrar columnas en tabla 'staff'
        if (Schema::hasTable('staff')) {
            Schema::table('staff', function (Blueprint $table) {
                if (Schema::hasColumn('staff', 'nombre')) {
                    $table->renameColumn('nombre', 'name');
                }
                if (Schema::hasColumn('staff', 'cargo')) {
                    $table->renameColumn('cargo', 'position');
                }
                if (Schema::hasColumn('staff', 'telefono')) {
                    $table->renameColumn('telefono', 'phone');
                }
            });
        }

        // 6. Renombrar columnas en tabla 'incidents'
        if (Schema::hasTable('incidents')) {
            Schema::table('incidents', function (Blueprint $table) {
                if (Schema::hasColumn('incidents', 'titulo')) {
                    $table->renameColumn('titulo', 'title');
                }
                if (Schema::hasColumn('incidents', 'descripcion')) {
                    $table->renameColumn('descripcion', 'description');
                }
                if (Schema::hasColumn('incidents', 'sala')) {
                    $table->renameColumn('sala', 'room');
                }
                // Estado se maneja en migración separada por ser enum
                if (Schema::hasColumn('incidents', 'fecha_creacion')) {
                    $table->renameColumn('fecha_creacion', 'created_date');
                }
                if (Schema::hasColumn('incidents', 'resuelta_en')) {
                    $table->renameColumn('resuelta_en', 'resolved_at');
                }
                if (Schema::hasColumn('incidents', 'comentario')) {
                    $table->renameColumn('comentario', 'comment');
                }
            });
        }

        // 7. Renombrar columnas en tabla 'courses'
        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (Schema::hasColumn('courses', 'nombre')) {
                    $table->renameColumn('nombre', 'name');
                }
                if (Schema::hasColumn('courses', 'programa')) {
                    $table->renameColumn('programa', 'program');
                }
            });
        }

        // 8. Renombrar columnas en tabla 'events'
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (Schema::hasColumn('events', 'titulo')) {
                    $table->renameColumn('titulo', 'title');
                }
                if (Schema::hasColumn('events', 'descripcion')) {
                    $table->renameColumn('descripcion', 'description');
                }
                if (Schema::hasColumn('events', 'fecha')) {
                    $table->renameColumn('fecha', 'date');
                }
                if (Schema::hasColumn('events', 'hora_inicio')) {
                    $table->renameColumn('hora_inicio', 'start_time');
                }
                if (Schema::hasColumn('events', 'hora_fin')) {
                    $table->renameColumn('hora_fin', 'end_time');
                }
            });
        }

        // 9. Renombrar columnas en tabla 'rooms'
        if (Schema::hasTable('rooms')) {
            Schema::table('rooms', function (Blueprint $table) {
                if (Schema::hasColumn('rooms', 'name')) {
                    // Ya está en inglés, pero verificar otras columnas
                }
                if (Schema::hasColumn('rooms', 'ubicacion')) {
                    $table->renameColumn('ubicacion', 'location');
                }
                if (Schema::hasColumn('rooms', 'capacidad')) {
                    $table->renameColumn('capacidad', 'capacity');
                }
                if (Schema::hasColumn('rooms', 'descripcion')) {
                    $table->renameColumn('descripcion', 'description');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir cambios en orden inverso
        
        // 1. Revertir columnas en tabla 'rooms'
        if (Schema::hasTable('rooms')) {
            Schema::table('rooms', function (Blueprint $table) {
                if (Schema::hasColumn('rooms', 'location')) {
                    $table->renameColumn('location', 'ubicacion');
                }
                if (Schema::hasColumn('rooms', 'capacity')) {
                    $table->renameColumn('capacity', 'capacidad');
                }
                if (Schema::hasColumn('rooms', 'description')) {
                    $table->renameColumn('description', 'descripcion');
                }
            });
        }

        // 2. Revertir columnas en tabla 'events'
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (Schema::hasColumn('events', 'title')) {
                    $table->renameColumn('title', 'titulo');
                }
                if (Schema::hasColumn('events', 'description')) {
                    $table->renameColumn('description', 'descripcion');
                }
                if (Schema::hasColumn('events', 'date')) {
                    $table->renameColumn('date', 'fecha');
                }
                if (Schema::hasColumn('events', 'start_time')) {
                    $table->renameColumn('start_time', 'hora_inicio');
                }
                if (Schema::hasColumn('events', 'end_time')) {
                    $table->renameColumn('end_time', 'hora_fin');
                }
            });
        }

        // 3. Revertir columnas en tabla 'courses'
        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (Schema::hasColumn('courses', 'name')) {
                    $table->renameColumn('name', 'nombre');
                }
                if (Schema::hasColumn('courses', 'program')) {
                    $table->renameColumn('program', 'programa');
                }
            });
        }

        // 4. Revertir columnas en tabla 'incidents'
        if (Schema::hasTable('incidents')) {
            Schema::table('incidents', function (Blueprint $table) {
                if (Schema::hasColumn('incidents', 'title')) {
                    $table->renameColumn('title', 'titulo');
                }
                if (Schema::hasColumn('incidents', 'description')) {
                    $table->renameColumn('description', 'descripcion');
                }
                if (Schema::hasColumn('incidents', 'room')) {
                    $table->renameColumn('room', 'sala');
                }
                // Estado se maneja en migración separada por ser enum
                if (Schema::hasColumn('incidents', 'created_date')) {
                    $table->renameColumn('created_date', 'fecha_creacion');
                }
                if (Schema::hasColumn('incidents', 'resolved_at')) {
                    $table->renameColumn('resolved_at', 'resuelta_en');
                }
                if (Schema::hasColumn('incidents', 'comment')) {
                    $table->renameColumn('comment', 'comentario');
                }
            });
        }

        // 5. Revertir columnas en tabla 'staff'
        if (Schema::hasTable('staff')) {
            Schema::table('staff', function (Blueprint $table) {
                if (Schema::hasColumn('staff', 'name')) {
                    $table->renameColumn('name', 'nombre');
                }
                if (Schema::hasColumn('staff', 'position')) {
                    $table->renameColumn('position', 'cargo');
                }
                if (Schema::hasColumn('staff', 'phone')) {
                    $table->renameColumn('phone', 'telefono');
                }
            });
        }

        // 6. Revertir columnas en tabla 'programs'
        if (Schema::hasTable('programs')) {
            Schema::table('programs', function (Blueprint $table) {
                if (Schema::hasColumn('programs', 'name')) {
                    $table->renameColumn('name', 'nombre');
                }
            });
        }

        // 7. Revertir columnas en tabla 'announcements'
        if (Schema::hasTable('announcements')) {
            Schema::table('announcements', function (Blueprint $table) {
                if (Schema::hasColumn('announcements', 'title')) {
                    $table->renameColumn('title', 'titulo');
                }
                if (Schema::hasColumn('announcements', 'content')) {
                    $table->renameColumn('content', 'contenido');
                }
                if (Schema::hasColumn('announcements', 'announcement_type')) {
                    $table->renameColumn('announcement_type', 'tipo_novedad');
                }
                if (Schema::hasColumn('announcements', 'is_urgent')) {
                    $table->renameColumn('is_urgent', 'es_urgente');
                }
                if (Schema::hasColumn('announcements', 'expiration_date')) {
                    $table->renameColumn('expiration_date', 'fecha_expiracion');
                }
                if (Schema::hasColumn('announcements', 'is_public')) {
                    $table->renameColumn('is_public', 'visible_publico');
                }
                if (Schema::hasColumn('announcements', 'visible_roles')) {
                    $table->renameColumn('visible_roles', 'roles_visibles');
                }
            });
        }

        // 8. Revertir columnas en tabla 'classes'
        if (Schema::hasTable('classes')) {
            Schema::table('classes', function (Blueprint $table) {
                if (Schema::hasColumn('classes', 'day')) {
                    $table->renameColumn('day', 'dia');
                }
                if (Schema::hasColumn('classes', 'start_time')) {
                    $table->renameColumn('start_time', 'hora_inicio');
                }
                if (Schema::hasColumn('classes', 'end_time')) {
                    $table->renameColumn('end_time', 'hora_fin');
                }
                if (Schema::hasColumn('classes', 'zoom_url')) {
                    $table->renameColumn('zoom_url', 'url_zoom');
                }
            });
        }

        // 9. Revertir nombres de tablas
        if (Schema::hasTable('classes')) {
            Schema::rename('classes', 'clases');
        }
        
        if (Schema::hasTable('announcements')) {
            Schema::rename('announcements', 'novedades');
        }
        
        if (Schema::hasTable('programs')) {
            Schema::rename('programs', 'magisters');
        }
    }
};
