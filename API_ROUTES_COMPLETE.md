# 🚀 API Routes Completa - Web FEN

## 📋 **RESUMEN DE TODAS LAS RUTAS API**

### 🔐 **AUTENTICACIÓN**
```
POST   /api/register              - Registro de usuario
POST   /api/login                 - Login de usuario
GET    /api/user                  - Usuario autenticado (requiere auth)
POST   /api/logout                - Logout (requiere auth)
GET    /api/profile               - Perfil de usuario (requiere auth)
```

### 🔍 **BÚSQUEDA GLOBAL**
```
GET    /api/search                - Búsqueda global en toda la plataforma (requiere auth)
```

### 👑 **ADMINISTRACIÓN (Solo Administradores)**
```
GET    /api/admin/dashboard       - Dashboard con estadísticas completas
GET    /api/users                 - Listar usuarios (con filtros y paginación)
POST   /api/users                 - Crear usuario
GET    /api/users/{id}            - Mostrar usuario específico
PUT    /api/users/{id}            - Actualizar usuario
DELETE /api/users/{id}            - Eliminar usuario
GET    /api/users-statistics      - Estadísticas de usuarios
```

### 📊 **REPORTES DIARIOS (Daily Reports)**
```
GET    /api/daily-reports                    - Listar reportes diarios
POST   /api/daily-reports                    - Crear reporte diario
GET    /api/daily-reports/{id}               - Mostrar reporte específico
PUT    /api/daily-reports/{id}               - Actualizar reporte
DELETE /api/daily-reports/{id}               - Eliminar reporte
GET    /api/daily-reports/{id}/download-pdf  - Descargar PDF del reporte
GET    /api/daily-reports-statistics         - Estadísticas de reportes
GET    /api/daily-reports-resources          - Recursos para formularios
```

### 👥 **PERSONAL (Staff)**
```
GET    /api/staff                 - Listar personal (con filtros y paginación)
POST   /api/staff                 - Crear miembro del personal
GET    /api/staff/{id}            - Mostrar miembro específico
PUT    /api/staff/{id}            - Actualizar miembro
DELETE /api/staff/{id}            - Eliminar miembro
```

### 🎓 **PROGRAMAS DE MAGÍSTER**
```
GET    /api/magisters             - Listar programas de magíster
POST   /api/magisters             - Crear programa
GET    /api/magisters/{id}        - Mostrar programa específico
PUT    /api/magisters/{id}        - Actualizar programa
DELETE /api/magisters/{id}        - Eliminar programa
```

### 📚 **CURSOS**
```
GET    /api/courses               - Listar cursos (con filtros por magíster)
POST   /api/courses               - Crear curso
GET    /api/courses/{id}          - Mostrar curso específico
PUT    /api/courses/{id}          - Actualizar curso
DELETE /api/courses/{id}          - Eliminar curso
```

### 🏫 **CLASES**
```
GET    /api/clases                - Listar clases (con filtros y paginación)
POST   /api/clases                - Crear clase
GET    /api/clases/{id}           - Mostrar clase específica
PUT    /api/clases/{id}           - Actualizar clase
DELETE /api/clases/{id}           - Eliminar clase
```

### 🏢 **SALAS**
```
GET    /api/rooms                 - Listar salas (con filtros y paginación)
POST   /api/rooms                 - Crear sala
GET    /api/rooms/{id}            - Mostrar sala específica
PUT    /api/rooms/{id}            - Actualizar sala
DELETE /api/rooms/{id}            - Eliminar sala
GET    /api/rooms-public          - Listar salas (vista pública)
```

### 📅 **PERÍODOS ACADÉMICOS**
```
GET    /api/periods               - Listar períodos (con filtros y paginación)
POST   /api/periods               - Crear período
GET    /api/periods/{id}          - Mostrar período específico
PUT    /api/periods/{id}          - Actualizar período
DELETE /api/periods/{id}          - Eliminar período
PUT    /api/periods/update-to-next-year - Actualizar fechas al próximo año
POST   /api/periods/trimestre-siguiente - Obtener trimestre siguiente
POST   /api/periods/trimestre-anterior - Obtener trimestre anterior
GET    /api/periods/periodo-por-fecha/{fecha} - Obtener período por fecha
```

### 🚨 **INCIDENCIAS**
```
GET    /api/incidents             - Listar incidencias (con filtros y paginación)
POST   /api/incidents             - Crear incidencia
GET    /api/incidents/{id}        - Mostrar incidencia específica
PUT    /api/incidents/{id}        - Actualizar incidencia
DELETE /api/incidents/{id}        - Eliminar incidencia
GET    /api/incidents-statistics  - Estadísticas de incidencias
```

### 🆘 **EMERGENCIAS**
```
GET    /api/emergencies           - Listar emergencias
POST   /api/emergencies           - Crear emergencia
GET    /api/emergencies/{id}      - Mostrar emergencia específica
PUT    /api/emergencies/{id}      - Actualizar emergencia
DELETE /api/emergencies/{id}      - Eliminar emergencia
POST   /api/emergencies/{id}/deactivate - Desactivar emergencia
GET    /api/emergencies/active    - Obtener emergencia activa
```

### 📅 **EVENTOS**
```
GET    /api/events                - Listar eventos (con filtros de calendario)
POST   /api/events                - Crear evento
GET    /api/events/{id}           - Mostrar evento específico
PUT    /api/events/{id}           - Actualizar evento
DELETE /api/events/{id}           - Eliminar evento
GET    /api/events/calendario     - Eventos para calendario móvil
GET    /api/events/public         - Eventos públicos (sin autenticación)
```

### 🌐 **RUTAS PÚBLICAS (Sin Autenticación)**
```
GET    /api/trimestre-siguiente   - Obtener trimestre siguiente
GET    /api/trimestre-anterior    - Obtener trimestre anterior
GET    /api/periodo-por-fecha     - Obtener período por fecha
GET    /api/periodo-fecha-inicio  - Obtener fecha de inicio de período
GET    /api/rooms-public          - Listar salas (público)
GET    /api/events/public         - Listar eventos (público)
```

## 🔧 **CARACTERÍSTICAS DE LA API**

### ✅ **Validaciones Implementadas**
- Campos obligatorios para reportes diarios: `hora`, `escala`, `programa`, `area`
- Validación de escalas de severidad (1-10)
- Validación de roles de usuario
- Validación de fechas y rangos
- Validación de archivos e imágenes

### 📊 **Funcionalidades de Reportes Diarios**
- Indicador de severidad visual (1-10)
- Iconos SVG para cada nivel de severidad
- Campos: horario, escala, programa, área, tarea
- Generación de PDF con escala de severidad
- Estadísticas por programa y área

### 🛡️ **Seguridad**
- Autenticación con Laravel Sanctum
- Middleware de roles para administradores
- Validación de permisos
- Protección contra eliminación del último administrador

### 📱 **Respuestas Estandarizadas**
```json
{
    "success": true,
    "data": { ... },
    "message": "Mensaje descriptivo"
}
```

### 🔍 **Filtros y Búsquedas**
- Búsqueda por texto en todos los controladores
- Filtros por fecha, rol, estado, etc.
- Paginación configurable
- Ordenamiento personalizable

## 🎯 **ESTADO FINAL**

**✅ 100% de los controladores funcionando correctamente**
**✅ Todas las validaciones implementadas**
**✅ Respuestas API estandarizadas**
**✅ Seguridad y permisos configurados**
**✅ Documentación completa**

¡Tu API está lista para producción! 🚀✨
