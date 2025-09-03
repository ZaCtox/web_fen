# API de Gestión Académica - Documentación para Android Studio

## Descripción General

Esta API RESTful está diseñada para ser consumida desde aplicaciones móviles en Android Studio (Kotlin). Proporciona endpoints para la gestión completa de una plataforma académica, incluyendo salas, programas, asignaturas, eventos, incidencias y estadísticas.

## Características Técnicas

- **Framework**: Laravel 12
- **Autenticación**: Laravel Sanctum (tokens JWT)
- **Base de Datos**: MySQL
- **Documentación**: Swagger/OpenAPI
- **CORS**: Configurado para aplicaciones móviles
- **Reportes**: Generación de PDF con DomPDF
- **Imágenes**: Integración con Cloudinary

## Base URL

```
http://localhost:8000/api
```

## Autenticación

### Obtener Token de Acceso

```http
POST /sanctum/csrf-cookie
POST /login
```

**Headers requeridos:**
```
Accept: application/json
Content-Type: application/json
```

**Body del login:**
```json
{
    "email": "usuario@ejemplo.com",
    "password": "password123"
}
```

**Response exitoso:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Usuario Ejemplo",
            "email": "usuario@ejemplo.com",
            "roles": ["administrador"]
        },
        "token": "1|abc123..."
    }
}
```

### Usar Token en Requests

```http
Authorization: Bearer 1|abc123...
Accept: application/json
```

## Endpoints Principales

### 1. Gestión de Programas de Magíster

#### Listar Programas
```http
GET /programs
Authorization: Bearer {token}
```

#### Crear Programa
```http
POST /programs
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Magíster en Ciencias de la Computación",
    "description": "Programa de posgrado en computación",
    "duration_trimesters": 8
}
```

#### Actualizar Programa
```http
PUT /programs/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Magíster en Ciencias de la Computación - Actualizado",
    "duration_trimesters": 10
}
```

#### Eliminar Programa
```http
DELETE /programs/{id}
Authorization: Bearer {token}
```

### 2. Gestión de Asignaturas

#### Listar Asignaturas
```http
GET /subjects?program_id=1&academic_year=2024&trimester=1
Authorization: Bearer {token}
```

#### Crear Asignatura
```http
POST /subjects
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Algoritmos Avanzados",
    "description": "Curso de algoritmos y estructuras de datos",
    "credits": 6,
    "program_id": 1,
    "trimester": 1,
    "academic_year": 2024
}
```

### 3. Gestión de Salas

#### Listar Salas
```http
GET /rooms
Authorization: Bearer {token}
```

#### Asignar Sala a Asignatura
```http
POST /room-assignments
Authorization: Bearer {token}
Content-Type: application/json

{
    "room_id": 1,
    "subject_id": 1,
    "academic_year": 2024,
    "trimester": 1,
    "schedule": "LUNES 08:00-10:00"
}
```

### 4. Calendario Académico

#### Listar Eventos
```http
GET /academic-events?event_type=clase&room_id=1&start_date=2024-01-01&end_date=2024-01-31
Authorization: Bearer {token}
```

#### Crear Evento
```http
POST /academic-events
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Clase de Algoritmos",
    "description": "Introducción a algoritmos de ordenamiento",
    "start_date": "2024-01-15 08:00:00",
    "end_date": "2024-01-15 10:00:00",
    "room_id": 1,
    "magister_id": 1,
    "event_type": "clase",
    "color": "#3B82F6"
}
```

### 5. Gestión de Incidencias

#### Listar Incidencias
```http
GET /incidents?estado=pendiente&room_id=1
Authorization: Bearer {token}
```

#### Crear Incidencia
```http
POST /incidents
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
    "titulo": "Proyector no funciona",
    "descripcion": "El proyector de la sala no enciende",
    "room_id": 1,
    "estado": "pendiente",
    "images[]": [file1, file2]
}
```

#### Actualizar Estado de Incidencia
```http
PUT /incidents/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "estado": "en_progreso",
    "comentario": "Técnico asignado para revisar"
}
```

#### Agregar Imágenes a Incidencia
```http
POST /incidents/{id}/images
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
    "images[]": [file1, file2]
}
```

#### Generar Reporte PDF
```http
GET /incidents/report/pdf?estado=resuelta&start_date=2024-01-01&end_date=2024-01-31
Authorization: Bearer {token}
```

### 6. Dashboard Administrativo

#### Estadísticas Generales
```http
GET /dashboard/stats
Authorization: Bearer {token}
```

#### Tendencia de Incidencias
```http
GET /dashboard/incidents-trend?days=30
Authorization: Bearer {token}
```

#### Uso de Salas
```http
GET /dashboard/room-usage?academic_year=2024&trimester=1
Authorization: Bearer {token}
```

#### Actividad de Usuarios
```http
GET /dashboard/user-activity?days=7
Authorization: Bearer {token}
```

#### Resumen de Satisfacción
```http
GET /dashboard/satisfaction-summary
Authorization: Bearer {token}
```

### 7. Evaluación de Satisfacción

#### Registrar Evaluación
```http
POST /satisfaction
Authorization: Bearer {token}
Content-Type: application/json

{
    "rating": 5,
    "comment": "Excelente sistema, muy fácil de usar",
    "category": "general"
}
```

## Códigos de Respuesta

- **200**: Éxito
- **201**: Creado exitosamente
- **400**: Error de validación
- **401**: No autenticado
- **403**: Acceso denegado
- **404**: No encontrado
- **422**: Error de validación de datos
- **500**: Error interno del servidor

## Estructura de Respuestas

### Respuesta Exitosa
```json
{
    "success": true,
    "message": "Operación realizada exitosamente",
    "data": {
        // Datos de la respuesta
    }
}
```

### Respuesta de Error
```json
{
    "success": false,
    "message": "Descripción del error",
    "errors": {
        "campo": ["Mensaje de error específico"]
    }
}
```

## Ejemplos de Uso en Kotlin

### Configuración de Retrofit

```kotlin
interface ApiService {
    @GET("programs")
    suspend fun getPrograms(): Response<ApiResponse<List<Program>>>
    
    @POST("programs")
    suspend fun createProgram(@Body program: ProgramRequest): Response<ApiResponse<Program>>
    
    @GET("incidents")
    suspend fun getIncidents(@QueryMap filters: Map<String, String>): Response<ApiResponse<List<Incident>>>
}

// Configuración de Retrofit
val retrofit = Retrofit.Builder()
    .baseUrl("http://localhost:8000/api/")
    .addConverterFactory(GsonConverterFactory.create())
    .client(OkHttpClient.Builder()
        .addInterceptor { chain ->
            val request = chain.request().newBuilder()
                .addHeader("Authorization", "Bearer $token")
                .addHeader("Accept", "application/json")
                .build()
            chain.proceed(request)
        }
        .build())
    .build()
```

### Modelos de Datos

```kotlin
data class ApiResponse<T>(
    val success: Boolean,
    val message: String?,
    val data: T?
)

data class Program(
    val id: Int,
    val name: String,
    val description: String?,
    val duration_trimesters: Int,
    val is_active: Boolean
)

data class Incident(
    val id: Int,
    val titulo: String,
    val descripcion: String,
    val room_id: Int,
    val estado: String,
    val nro_ticket: String,
    val created_at: String,
    val room: Room?,
    val user: User?
)
```

### Llamadas a la API

```kotlin
class ProgramRepository(private val apiService: ApiService) {
    
    suspend fun getPrograms(): Result<List<Program>> {
        return try {
            val response = apiService.getPrograms()
            if (response.isSuccessful && response.body()?.success == true) {
                Result.success(response.body()?.data ?: emptyList())
            } else {
                Result.failure(Exception(response.body()?.message ?: "Error desconocido"))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }
    
    suspend fun createProgram(program: ProgramRequest): Result<Program> {
        return try {
            val response = apiService.createProgram(program)
            if (response.isSuccessful && response.body()?.success == true) {
                Result.success(response.body()?.data!!)
            } else {
                Result.failure(Exception(response.body()?.message ?: "Error desconocido"))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }
}
```

## Manejo de Errores

### Interceptor de Errores

```kotlin
class ErrorInterceptor : Interceptor {
    override fun intercept(chain: Interceptor.Chain): Response {
        val response = chain.proceed(chain.request())
        
        when (response.code) {
            401 -> {
                // Token expirado, redirigir al login
                EventBus.getDefault().post(TokenExpiredEvent())
            }
            403 -> {
                // Acceso denegado
                EventBus.getDefault().post(AccessDeniedEvent())
            }
            422 -> {
                // Error de validación
                val errorBody = response.body?.string()
                // Procesar errores de validación
            }
        }
        
        return response
    }
}
```

## Configuración de CORS

La API está configurada para permitir acceso desde cualquier origen, incluyendo aplicaciones móviles. Los headers CORS están configurados para permitir:

- `Access-Control-Allow-Origin: *`
- `Access-Control-Allow-Methods: *`
- `Access-Control-Allow-Headers: *`
- `Access-Control-Allow-Credentials: true`

## Documentación Swagger

La documentación interactiva de la API está disponible en:

```
http://localhost:8000/api/documentation
```

## Consideraciones de Seguridad

1. **Autenticación**: Todos los endpoints requieren autenticación mediante tokens Sanctum
2. **Autorización**: Los endpoints están protegidos por roles (administrador, docente, estudiante)
3. **Validación**: Todos los datos de entrada son validados antes de ser procesados
4. **Logging**: Se registran todos los accesos a la API para auditoría
5. **Rate Limiting**: Implementar rate limiting en producción para prevenir abuso

## Testing

### Endpoints de Prueba

```http
GET /health
```

**Response:**
```json
{
    "status": "OK",
    "timestamp": "2024-01-15T10:30:00.000000Z",
    "message": "API funcionando correctamente"
}
```

## Soporte y Contacto

Para soporte técnico o consultas sobre la API, contactar al equipo de desarrollo.

---

**Versión**: 1.0.0  
**Última actualización**: Enero 2024  
**Framework**: Laravel 12
