# 🌐 API Pública Completa - Web FEN

## 📅 Fecha: 15 de Octubre 2025

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

