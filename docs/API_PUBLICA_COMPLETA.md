# 🌐 API Completa - Web FEN (Actualizada)

## 📅 Fecha: Diciembre 2024
## 🔄 Última actualización: Cambios de roles y nuevos endpoints

## ⚠️ **CAMBIOS IMPORTANTES**
- ❌ **Rol `administrador` eliminado** - Ya no existe
- ❌ **Rol `visor` eliminado** - Completamente removido
- ✅ **Nuevos roles**: `director_administrativo`, `decano`, `director_programa`, `asistente_programa`, `asistente_postgrado`, `docente`, `técnico`, `auxiliar`
- ✅ **Nuevo endpoint**: `/api/analytics` para estadísticas
- ✅ **Filtros mejorados** en todos los controladores

## ✅ RUTAS PÚBLICAS (Sin Autenticación)

Estas rutas NO requieren token de autenticación y pueden ser consumidas libremente por la app móvil o cualquier cliente.

---

## 📋 LISTADO COMPLETO DE ENDPOINTS PÚBLICOS

### 🎓 **Programas de Magíster**
```
GET /api/public/magisters
GET /api/public/magisters-with-course-count
```

**Respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "Magíster en Finanzas",
      "color": "#3B82F6",
      "encargado": "Dr. Juan Pérez",
      "telefono": "+56912345678",
      "correo": "juan.perez@fen.cl",
      "courses_count": 12
    }
  ]
}
```

---

### 📅 **Eventos del Calendario**
```
GET /api/public/events?start=YYYY-MM-DD&end=YYYY-MM-DD&magister_id=1&room_id=1
```

**Parámetros:**
- `start` - Fecha inicio (YYYY-MM-DD)
- `end` - Fecha fin (YYYY-MM-DD)
- `magister_id` - Filtrar por programa (opcional)
- `room_id` - Filtrar por sala (opcional)

**Respuesta:**
```json
{
  "status": "success",
  "data": [
    {
      "id": "event-1",
      "title": "Clase de Finanzas Corporativas",
      "start": "2025-10-15 09:00:00",
      "end": "2025-10-15 13:00:00",
      "room": { "id": 1, "name": "Sala A101" },
      "magister": { "id": 1, "name": "Magíster en Finanzas" },
      "backgroundColor": "#3B82F6",
      "type": "clase",
      "modality": "presencial"
    }
  ],
  "meta": {
    "total": 25,
    "public_view": true
  }
}
```

---

### 👥 **Personal/Staff**
```
GET /api/public/staff
```

**Respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "Ana López",
      "cargo": "Asistente de Postgrado",
      "telefono": "+56912345678",
      "email": "ana.lopez@fen.cl",
      "foto": "https://cloudinary.com/..."
    }
  ]
}
```

---

### 🏢 **Salas**
```
GET /api/public/rooms
```

**Respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Sala A101",
      "location": "Edificio Principal, Piso 1",
      "capacity": 40,
      "has_projector": true,
      "has_computer": true,
      "has_air_conditioning": true
    }
  ]
}
```

---

### 📚 **Cursos**
```
GET /api/public/courses
GET /api/public/courses/magister/{magisterId}
GET /api/public/courses/magister/{magisterId}/paginated
```

**Respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "Finanzas Corporativas",
      "magister_id": 1,
      "magister": {
        "id": 1,
        "nombre": "Magíster en Finanzas",
        "color": "#3B82F6"
      }
    }
  ]
}
```

---

### 📰 **Novedades** ⭐ (Recién agregado)
```
GET /api/public/novedades
GET /api/public/novedades/{id}
```

**GET /api/public/novedades** - Lista de novedades activas (no expiradas)

**Respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "titulo": "Suspensión de clases - 15 de Octubre",
      "contenido": "Las clases están suspendidas...",
      "tipo": "Anuncio Importante",
      "color": "red",
      "icono": "alert",
      "es_urgente": true,
      "magister": {
        "id": 1,
        "nombre": "Magíster en Finanzas"
      },
      "user": {
        "id": 1,
        "name": "Admin User"
      },
      "fecha_expiracion": "2025-10-16 18:00:00",
      "acciones": null,
      "created_at": "2025-10-15T10:00:00.000000Z"
    }
  ],
  "message": "Novedades activas obtenidas exitosamente"
}
```

**GET /api/public/novedades/{id}** - Detalle de una novedad

---

### 📄 **Informes/Archivos FEN** ⭐ (Recién agregado)
```
GET /api/public/informes
GET /api/public/informes/{id}
GET /api/public/informes/{id}/download
```

**GET /api/public/informes** - Lista de todos los informes

**Parámetros opcionales:**
- `search` - Buscar por nombre
- `tipo` - Filtrar por tipo
- `magister_id` - Filtrar por programa
- `user_id` - Filtrar por usuario que subió
- `per_page` - Paginación (default: 15)

**Respuesta:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "nombre": "Reglamento de Postgrado 2025",
        "tipo": "Reglamento",
        "archivo": "informes/abc123.pdf",
        "mime": "application/pdf",
        "user": {
          "id": 1,
          "name": "Admin User"
        },
        "magister": {
          "id": 1,
          "nombre": "Magíster en Finanzas",
          "color": "#3B82F6"
        },
        "created_at": "2025-10-15T10:30:00.000000Z"
      }
    ],
    "total": 25,
    "per_page": 15
  },
  "message": "Informes obtenidos exitosamente"
}
```

**GET /api/public/informes/{id}/download** - Descargar archivo

**Respuesta:** Archivo binario (PDF, Word, Excel, etc.)

---

### 🚨 **Emergencia Activa**
```
GET /api/emergencies/active
```

**Respuesta:**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "title": "Suspensión de clases",
    "message": "Por motivo de actividad especial...",
    "active": true,
    "expires_at": "2025-10-16 18:00:00"
  }
}
```

O si no hay emergencia activa:
```json
{
  "status": "success",
  "data": null
}
```

---

## 📊 RESUMEN DE RUTAS PÚBLICAS

### Total de Endpoints Públicos: 15

| Recurso | Endpoints | Autenticación |
|---------|-----------|---------------|
| Magisters | 2 | ❌ No requerida |
| Events | 1 | ❌ No requerida |
| Staff | 1 | ❌ No requerida |
| Rooms | 1 | ❌ No requerida |
| Courses | 3 | ❌ No requerida |
| Novedades | 2 | ❌ No requerida |
| Informes | 3 | ❌ No requerida |
| Emergencies | 1 | ❌ No requerida |

**Total:** 14 endpoints públicos ✅

---

## 🎯 USO EN LA APP ANDROID

### Para la Fase 1 de tu app, puedes consumir SIN LOGIN:

#### 1. **Homepage:**
```kotlin
GET /api/public/novedades           // Novedades destacadas
GET /api/emergencies/active         // Banner de emergencia
```

#### 2. **Calendario:**
```kotlin
val today = LocalDate.now()
val startOfMonth = today.withDayOfMonth(1)
val endOfMonth = today.withDayOfMonth(today.lengthOfMonth())

GET /api/public/events?start=$startOfMonth&end=$endOfMonth
```

#### 3. **Salas:**
```kotlin
GET /api/public/rooms
```

#### 4. **Personal:**
```kotlin
GET /api/public/staff
```

#### 5. **Programas:**
```kotlin
GET /api/public/magisters-with-course-count
```

#### 6. **Archivos/Documentos:**
```kotlin
GET /api/public/informes           // Lista
GET /api/public/informes/1/download // Descargar
```

---

## 🔐 SEGURIDAD

### Rutas Públicas:
- ✅ Accesibles sin token
- ✅ Solo lectura (GET)
- ✅ No permiten crear/editar/eliminar
- ✅ Seguras para consumo público

### Rutas Protegidas (requieren auth:sanctum):
- 🔒 POST, PUT, DELETE de todos los recursos
- 🔒 Estadísticas detalladas
- 🔒 Dashboard de administrador
- 🔒 Gestión de usuarios

---

## 📱 EJEMPLO DE USO EN LA APP

### Sin Login (Público):
```kotlin
// Ver novedades
api.getPublicNovedades()

// Ver calendario del mes
api.getPublicEvents(start, end)

// Ver salas disponibles
api.getPublicRooms()

// Descargar reglamento
api.downloadInforme(id)
```

### Con Login (Autenticado):
```kotlin
// Todo lo anterior +
api.getDailyReports()
api.createIncident(...)
api.getAdminDashboard()
```

---

## ✅ CONCLUSIÓN

**API Pública Completada** 🎉

### Endpoints públicos disponibles:
- ✅ Magisters (2)
- ✅ Events (1)
- ✅ Staff (1)
- ✅ Rooms (1)
- ✅ Courses (3)
- ✅ **Novedades (2)** ⭐ Agregado
- ✅ **Informes (3)** ⭐ Agregado
- ✅ Emergencies (1)

**Total:** 14 endpoints públicos ✅

**¡Tu app Android puede mostrar información pública sin necesidad de login!** 🚀

---

**Estado:** ✅ COMPLETADO
**Rutas públicas:** 14 endpoints
**Informes públicos:** ✅ Sí (listar, ver, descargar)
**Novedades públicas:** ✅ Sí (activas, detalle)

---

## 🔐 **ENDPOINTS PROTEGIDOS (Con Autenticación)**

### **Autenticación**
```http
POST /api/register
POST /api/login
GET  /api/user
POST /api/logout
```

### **Usuarios** (Solo director_administrativo, decano)
```http
GET    /api/users
POST   /api/users
GET    /api/users/{id}
PUT    /api/users/{id}
DELETE /api/users/{id}
GET    /api/users-statistics
```

### **Staff** (CRUD: director_administrativo, decano | Ver: todos)
```http
GET    /api/staff
POST   /api/staff
GET    /api/staff/{id}
PUT    /api/staff/{id}
DELETE /api/staff/{id}
```

### **Cursos** (Con filtros mejorados)
```http
GET    /api/courses?search=nombre&magister_id=1&anio_ingreso=2024&anio=1&trimestre=1
POST   /api/courses
GET    /api/courses/{id}
PUT    /api/courses/{id}
DELETE /api/courses/{id}
```

### **Daily Reports** (Solo asistente_postgrado, decano)
```http
GET    /api/daily-reports
POST   /api/daily-reports
GET    /api/daily-reports/{id}
PUT    /api/daily-reports/{id}
DELETE /api/daily-reports/{id}
GET    /api/daily-reports/{id}/download-pdf
GET    /api/daily-reports-statistics
GET    /api/daily-reports-resources
```

### **Novedades** (Solo director_administrativo, decano, asistente_postgrado)
```http
GET    /api/novedades
POST   /api/novedades
GET    /api/novedades/{id}
PUT    /api/novedades/{id}
DELETE /api/novedades/{id}
GET    /api/novedades-statistics
GET    /api/novedades-resources
```

### **Analytics** (Solo director_administrativo, decano, director_programa, asistente_postgrado)
```http
GET    /api/analytics
GET    /api/analytics/period-stats?anio_ingreso=2024&anio=1&trimestre=1
```

### **Otros Endpoints Protegidos**
```http
# Incidencias
GET    /api/incidents
POST   /api/incidents
GET    /api/incidents/{id}
PUT    /api/incidents/{id}
DELETE /api/incidents/{id}
GET    /api/incidents-statistics

# Clases
GET    /api/clases
POST   /api/clases
GET    /api/clases/{id}
PUT    /api/clases/{id}
DELETE /api/clases/{id}

# Salas
GET    /api/rooms
POST   /api/rooms
GET    /api/rooms/{id}
PUT    /api/rooms/{id}
DELETE /api/rooms/{id}

# Períodos
GET    /api/periods
POST   /api/periods
GET    /api/periods/{id}
PUT    /api/periods/{id}
DELETE /api/periods/{id}

# Emergencias
GET    /api/emergencies
POST   /api/emergencies
PUT    /api/emergencies/{id}
DELETE /api/emergencies/{id}
PATCH  /api/emergencies/{id}/deactivate
```

---

## 🎯 **ROLES Y PERMISOS**

| **Rol** | **Descripción** | **Permisos Especiales** |
|---------|----------------|-------------------------|
| `director_administrativo` | Máximo nivel | Acceso total al sistema |
| `decano` | Solo lectura | Acceso a reportes diarios |
| `director_programa` | Gestión académica | Solo lectura en "Nuestro Equipo" |
| `asistente_programa` | Apoyo académico | Gestión de salas y soporte |
| `asistente_postgrado` | Soporte | Acceso exclusivo a reportes diarios |
| `docente` | Enseñanza | Solo calendario y clases |
| `técnico` | Soporte técnico | Solo incidencias |
| `auxiliar` | Apoyo básico | Solo incidencias |

---

## 📊 **FILTROS DISPONIBLES**

### **Cursos**
- `search` - Búsqueda por nombre
- `magister_id` - Filtrar por magíster
- `period_id` - Filtrar por período
- `anio_ingreso` - Filtrar por año de ingreso
- `anio` - Filtrar por año del período
- `trimestre` - Filtrar por trimestre

### **Incidencias**
- `search` - Búsqueda por título/descripción
- `estado` - Filtrar por estado
- `room_id` - Filtrar por sala
- `anio` - Filtrar por año
- `trimestre` - Filtrar por trimestre
- `anio_ingreso` - Filtrar por año de ingreso

### **Clases**
- `anio_ingreso` - Filtrar por año de ingreso
- `anio` - Filtrar por año del período
- `trimestre` - Filtrar por trimestre
- `magister` - Filtrar por magíster
- `room_id` - Filtrar por sala

---

**Estado:** ✅ COMPLETADO Y ACTUALIZADO
**Rutas públicas:** 14 endpoints
**Rutas protegidas:** 50+ endpoints
**Roles actualizados:** ✅ Sí
**Filtros mejorados:** ✅ Sí

