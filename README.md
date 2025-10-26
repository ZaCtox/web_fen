# 🎓 Plataforma de Gestión Académica - Escuela de Postgrados FEN

Sistema integral de gestión académica desarrollado para la Facultad de Economía y Negocios, diseñado para administrar programas de postgrado, clases, salas, eventos e incidencias.

## 🚀 Características Principales

### 📚 Gestión Académica
- **Clases**: Administración completa de sesiones de clase
- **Módulos**: Gestión de cursos y programas de postgrado
- **Periodos**: Control de períodos académicos
- **Salas**: Administración de espacios físicos y virtuales

### 📅 Calendario y Eventos
- Calendario académico integrado
- Gestión de eventos y actividades
- Visualización pública de calendarios

### 🔧 Soporte y Mantenimiento
- **Incidencias**: Sistema de reportes y seguimiento
- **Informes**: Generación de reportes académicos
- **Emergencias**: Gestión de situaciones de emergencia
- **Novedades**: Comunicaciones institucionales

### 📊 Analytics y Reportes
- Estadísticas de uso de salas
- Reportes de incidencias
- Analytics de clases y eventos
- Dashboard administrativo

## 🛠️ Tecnologías Utilizadas

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates + Alpine.js
- **Base de Datos**: MySQL
- **Estilos**: Tailwind CSS
- **PDF**: DomPDF
- **Cloud**: Cloudinary (para archivos)

## 📋 Requisitos del Sistema

- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js & NPM
- Servidor web (Apache/Nginx)

## ⚙️ Instalación

### 1. Clonar el repositorio
```bash
git clone [URL_DEL_REPOSITORIO]
cd Web_FEN
```

### 2. Instalar dependencias
```bash
composer install
npm install
```

### 3. Configurar entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar base de datos
Editar `.env` con tus credenciales:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fen_platform
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

### 5. Ejecutar migraciones y seeders
```bash
php artisan migrate
php artisan db:seed
```

### 6. Compilar assets
```bash
npm run build
# o para desarrollo:
npm run dev
```

### 7. Configurar permisos de storage
```bash
php artisan storage:link
```

## 👥 Sistema de Roles y Permisos

### 🔐 Roles Disponibles

| Rol | Descripción | Permisos |
|-----|-------------|----------|
| **Administrador** | Acceso completo al sistema | ✅ Todos los módulos |
| **Director Administrativo** | Gestión administrativa | ✅ Mismos permisos que Administrador |
| **Decano** | Supervisión académica | 👁️ Solo lectura en todos los módulos |
| **Director Programa** | Gestión de programa específico | ✅ Clases, módulos, incidencias |
| **Asistente Programa** | Apoyo administrativo | ✅ Clases, salas, incidencias |
| **Docente** | Profesor del programa | ✅ Sus clases y materiales |
| **Técnico** | Soporte técnico | ✅ Incidencias técnicas |
| **Auxiliar** | Apoyo operativo | ✅ Incidencias básicas |
| **Asistente Postgrado** | Apoyo académico | ✅ Clases, eventos, informes |
| **Visor** | Solo consulta | 👁️ Solo lectura en todos los módulos |

## 🧪 Usuarios de Prueba

El sistema incluye usuarios de prueba para diferentes roles:

### Credenciales de Acceso
```
Administrador:
- Email: admin@institucion.cl
- Contraseña: admin123

Decano:
- Email: decano@institucion.cl  
- Contraseña: decano123

Visor:
- Email: visor@institucion.cl
- Contraseña: visor123
```

## 📱 Módulos Principales

### 🏠 Dashboard
- Resumen general del sistema
- Estadísticas en tiempo real
- Accesos rápidos a módulos

### 📚 Gestión Académica
- **Clases**: Crear, editar y gestionar sesiones
- **Módulos**: Administrar cursos y programas
- **Periodos**: Control de períodos académicos
- **Salas**: Gestión de espacios físicos/virtuales

### 📅 Calendario
- Vista mensual/semanal de actividades
- Integración con clases y eventos
- Exportación de calendarios

### 🔧 Soporte
- **Incidencias**: Reportar y seguir problemas
- **Informes**: Generar reportes académicos
- **Emergencias**: Gestión de situaciones críticas

### 👥 Administración
- **Usuarios**: Gestión de cuentas y permisos
- **Equipo**: Información del personal
- **Novedades**: Comunicaciones institucionales

## 🔧 Comandos Útiles

### Desarrollo
```bash
# Servidor de desarrollo
php artisan serve

# Compilar assets en tiempo real
npm run dev

# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Base de Datos
```bash
# Resetear base de datos
php artisan migrate:fresh --seed

# Crear migración
php artisan make:migration nombre_migracion

# Crear seeder
php artisan make:seeder NombreSeeder
```

### Testing
```bash
# Ejecutar tests
php artisan test

# Tests específicos
php artisan test --filter NombreTest
```

## 📧 Sistema de Notificaciones

El sistema incluye:
- **Emails de bienvenida** personalizados
- **Notificaciones** de incidencias
- **Recordatorios** de clases y eventos
- **Alertas** de emergencias

## 🔒 Seguridad

- Autenticación robusta con Laravel Breeze
- Sistema de roles y permisos granular
- Validación de datos en frontend y backend
- Protección CSRF en todas las rutas
- Sanitización de inputs

## 📞 Soporte

Para soporte técnico o consultas sobre el sistema:

- **Email**: soporte@institucion.cl
- **Documentación**: Ver carpeta `docs/`
- **Issues**: Reportar en el repositorio del proyecto

## 📄 Licencia

Este proyecto es propiedad de la Facultad de Economía y Negocios y está destinado para uso institucional.

---

**Desarrollado con ❤️ para la gestión académica moderna**