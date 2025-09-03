# Instrucciones de Instalación - Sistema de Gestión Académica

## Requisitos Previos

- PHP 8.2 o superior
- Composer 2.0 o superior
- MySQL 8.0 o superior
- Node.js 18.0 o superior
- NPM o Yarn

## Pasos de Instalación

### 1. Clonar el Proyecto

```bash
git clone <url-del-repositorio>
cd Web_FEN
```

### 2. Instalar Dependencias de PHP

```bash
composer install
```

### 3. Configurar Variables de Entorno

```bash
cp env.example .env
```

Editar el archivo `.env` con la configuración de tu base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=academic_management
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

### 4. Generar Clave de Aplicación

```bash
php artisan key:generate
```

### 5. Configurar Cloudinary (Opcional)

Si vas a usar Cloudinary para manejo de imágenes, configurar en `.env`:

```env
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
CLOUDINARY_API_KEY=tu_api_key
CLOUDINARY_API_SECRET=tu_api_secret
CLOUDINARY_CLOUD_NAME=tu_cloud_name
```

### 6. Crear Base de Datos

```sql
CREATE DATABASE academic_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Ejecutar Migraciones

```bash
php artisan migrate
```

### 8. Ejecutar Seeders (Opcional)

```bash
php artisan db:seed
```

### 9. Instalar Dependencias de Node.js

```bash
npm install
```

### 10. Compilar Assets

```bash
npm run build
```

### 11. Configurar Permisos (Linux/Mac)

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 12. Iniciar Servidor de Desarrollo

```bash
php artisan serve
```

## Configuración de Base de Datos

### Estructura de Tablas

El sistema incluye las siguientes tablas principales:

- `users` - Usuarios del sistema
- `programs` - Programas de magíster
- `subjects` - Asignaturas
- `rooms` - Salas de clases
- `room_assignments` - Asignaciones de salas
- `academic_events` - Eventos académicos
- `incidents` - Incidencias
- `incident_images` - Imágenes de incidencias
- `user_satisfaction` - Evaluaciones de satisfacción
- `access_logs` - Logs de acceso

### Roles de Usuario

- **administrador**: Acceso completo al sistema
- **docente**: Gestión de eventos y incidencias
- **estudiante**: Visualización y reporte de incidencias

## Configuración de Swagger/OpenAPI

### 1. Publicar Configuración

```bash
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

### 2. Generar Documentación

```bash
php artisan l5-swagger:generate
```

### 3. Acceder a la Documentación

```
http://localhost:8000/api/documentation
```

## Configuración de CORS

El sistema está configurado para permitir acceso desde aplicaciones móviles. La configuración se encuentra en `config/cors.php`.

## Configuración de Sanctum

Laravel Sanctum está configurado para autenticación API. Los tokens se generan automáticamente al hacer login.

## Estructura del Proyecto

```
app/
├── Http/Controllers/Api/          # Controladores API
├── Models/                        # Modelos Eloquent
├── Http/Middleware/               # Middleware personalizado
└── Support/                       # Clases de soporte

database/
├── migrations/                    # Migraciones de base de datos
├── seeders/                       # Seeders para datos de prueba
└── factories/                     # Factories para testing

routes/
└── api.php                       # Rutas de la API

resources/
└── views/reports/                # Vistas para reportes PDF

config/
├── l5-swagger.php                # Configuración de Swagger
└── cors.php                      # Configuración de CORS
```

## Endpoints de Prueba

### Verificar Estado de la API

```bash
curl http://localhost:8000/api/health
```

### Autenticación

```bash
# Obtener CSRF token
curl -X GET http://localhost:8000/sanctum/csrf-cookie

# Login
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

## Comandos Útiles

### Limpiar Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Regenerar Autoload

```bash
composer dump-autoload
```

### Ver Rutas

```bash
php artisan route:list --path=api
```

### Verificar Migraciones

```bash
php artisan migrate:status
```

## Solución de Problemas

### Error de Conexión a Base de Datos

1. Verificar que MySQL esté ejecutándose
2. Verificar credenciales en `.env`
3. Verificar que la base de datos exista

### Error de Permisos

1. Verificar permisos en `storage/` y `bootstrap/cache/`
2. Ejecutar `chmod -R 775 storage bootstrap/cache`

### Error de Composer

1. Eliminar `vendor/` y `composer.lock`
2. Ejecutar `composer install`

### Error de Swagger

1. Verificar que L5-Swagger esté instalado
2. Ejecutar `php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"`
3. Ejecutar `php artisan l5-swagger:generate`

## Configuración de Producción

### 1. Cambiar APP_ENV

```env
APP_ENV=production
APP_DEBUG=false
```

### 2. Optimizar

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Configurar Web Server

Configurar Nginx o Apache para servir la aplicación Laravel.

### 4. Configurar Supervisor (Opcional)

Para manejo de colas en producción.

## Soporte

Para soporte técnico o consultas sobre la instalación, contactar al equipo de desarrollo.

---

**Versión**: 1.0.0  
**Última actualización**: Enero 2024  
**Framework**: Laravel 12
