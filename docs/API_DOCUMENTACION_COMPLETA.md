# 🌐 API Web FEN - Documentación Completa y Actualizada

## 📅 Diciembre 2024 - Versión 2.0

## ⚠️ **CAMBIOS IMPORTANTES EN ESTA VERSIÓN**
- ❌ **Rol `administrador` eliminado** - Ya no existe en el sistema
- ❌ **Rol `visor` eliminado** - Completamente removido
- ✅ **Nuevos roles**: `director_administrativo`, `decano`, `director_programa`, `asistente_programa`, `asistente_postgrado`, `docente`, `técnico`, `auxiliar`
- ✅ **Nuevo endpoint**: `/api/analytics` para estadísticas del sistema
- ✅ **Filtros mejorados** en todos los controladores
- ✅ **Middleware específico** por roles aplicado

---

## 🎯 **ROLES Y PERMISOS ACTUALIZADOS**

| **Rol** | **Descripción** | **Permisos Especiales** | **Acceso API** |
|---------|----------------|-------------------------|----------------|
| `director_administrativo` | Máximo nivel | Acceso total al sistema | Todos los endpoints |
| `decano` | Solo lectura | Acceso a reportes diarios | Ver todo, crear reportes diarios |
| `director_programa` | Gestión académica | Solo lectura en "Nuestro Equipo" | Gestión académica, analytics |
| `asistente_programa` | Apoyo académico | Gestión de salas y soporte | Clases, cursos, salas, incidencias |
| `asistente_postgrado` | Soporte | Acceso exclusivo a reportes diarios | Reportes diarios, novedades, analytics |
| `docente` | Enseñanza | Solo calendario y clases | Calendario, clases |
| `técnico` | Soporte técnico | Solo incidencias | Incidencias |
| `auxiliar` | Apoyo básico | Solo incidencias | Incidencias |

---

## 🌍 **ENDPOINTS PÚBLICOS (Sin Autenticación)**

Estas rutas NO requieren token y pueden ser consumidas libremente:

### **🎓 Programas de Magíster**
```http
GET /api/public/magisters
GET /api/public/magisters-with-course-count
```

### **📚 Cursos**
```http
GET /api/public/courses
GET /api/public/courses/years
GET /api/public/courses/magister/{magisterId}
GET /api/public/courses/magister/{magisterId}/paginated
```

### **📰 Novedades**
```http
GET /api/public/novedades
GET /api/public/novedades/{id}
```

### **📄 Informes**
```http
GET /api/public/informes
GET /api/public/informes/{id}
GET /api/public/informes/{id}/download
```

### **👥 Staff**
```http
GET /api/public/staff
```

### **🏛️ Salas**
```http
GET /api/public/rooms
```

### **📅 Clases**
```http
GET /api/public/clases
GET /api/public/clases/{id}
```

### **📅 Eventos**
```http
GET /api/public/events
```

### **🚨 Emergencias**
```http
GET /api/emergencies/active
```

---

## 🔐 **ENDPOINTS PROTEGIDOS (Con Autenticación)**

### **🔑 Autenticación**
```http
POST /api/register
POST /api/login
GET  /api/user
POST /api/logout
```

**Ejemplo de registro:**
```json
{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "rol": "docente"
}
```

### **👥 Usuarios** (Solo director_administrativo, decano)
```http
GET    /api/users
POST   /api/users
GET    /api/users/{id}
PUT    /api/users/{id}
DELETE /api/users/{id}
GET    /api/users-statistics
```

### **👔 Staff** (CRUD: director_administrativo, decano | Ver: todos)
```http
GET    /api/staff
POST   /api/staff
GET    /api/staff/{id}
PUT    /api/staff/{id}
DELETE /api/staff/{id}
```

### **📚 Cursos** (Con filtros mejorados)
```http
GET    /api/courses?search=nombre&magister_id=1&anio_ingreso=2024&anio=1&trimestre=1
POST   /api/courses
GET    /api/courses/{id}
PUT    /api/courses/{id}
DELETE /api/courses/{id}
```

### **📊 Daily Reports** (Solo asistente_postgrado, decano)
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

### **📰 Novedades** (Solo director_administrativo, decano, asistente_postgrado)
```http
GET    /api/novedades
POST   /api/novedades
GET    /api/novedades/{id}
PUT    /api/novedades/{id}
DELETE /api/novedades/{id}
GET    /api/novedades-statistics
GET    /api/novedades-resources
```

### **📊 Analytics** (Solo director_administrativo, decano, director_programa, asistente_postgrado)
```http
GET    /api/analytics
GET    /api/analytics/period-stats?anio_ingreso=2024&anio=1&trimestre=1
```

### **🚨 Incidencias**
```http
GET    /api/incidents
POST   /api/incidents
GET    /api/incidents/{id}
PUT    /api/incidents/{id}
DELETE /api/incidents/{id}
GET    /api/incidents-statistics
```

### **📅 Clases**
```http
GET    /api/clases
POST   /api/clases
GET    /api/clases/{id}
PUT    /api/clases/{id}
DELETE /api/clases/{id}
```

### **🏛️ Salas**
```http
GET    /api/rooms
POST   /api/rooms
GET    /api/rooms/{id}
PUT    /api/rooms/{id}
DELETE /api/rooms/{id}
```

### **📅 Períodos**
```http
GET    /api/periods
POST   /api/periods
GET    /api/periods/{id}
PUT    /api/periods/{id}
DELETE /api/periods/{id}
```

### **🚨 Emergencias**
```http
GET    /api/emergencies
POST   /api/emergencies
PUT    /api/emergencies/{id}
DELETE /api/emergencies/{id}
PATCH  /api/emergencies/{id}/deactivate
```

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

## 🧪 **GUÍA DE TESTING**

### **1. Testing Básico (Endpoints Públicos)**

```bash
# Cursos públicos
curl http://localhost:8000/api/public/courses

# Cursos con filtro
curl "http://localhost:8000/api/public/courses?anio_ingreso=2024"

# Años disponibles
curl http://localhost:8000/api/public/courses/years
```

### **2. Testing de Autenticación**

```bash
# Registro con rol válido
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Docente",
    "email": "docente@test.com",
    "password": "password123",
    "password_confirmation": "password123",
    "rol": "docente"
  }'

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "docente@test.com",
    "password": "password123"
  }'
```

### **3. Testing de Permisos**

```bash
# Intentar crear staff sin permisos (debería fallar con rol docente)
curl -X POST http://localhost:8000/api/staff \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Test Staff",
    "email": "staff@example.com",
    "cargo": "Test Cargo"
  }'
```

### **4. Testing de Analytics**

```bash
# Estadísticas generales
curl -X GET http://localhost:8000/api/analytics \
  -H "Authorization: Bearer YOUR_TOKEN"

# Estadísticas por período
curl -X GET "http://localhost:8000/api/analytics/period-stats?anio_ingreso=2024&anio=1&trimestre=1" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### **5. Testing de Filtros**

```bash
# Cursos con filtros combinados
curl -X GET "http://localhost:8000/api/courses?search=economia&anio_ingreso=2024&anio=1&trimestre=1" \
  -H "Authorization: Bearer YOUR_TOKEN"

# Incidencias con filtros
curl -X GET "http://localhost:8000/api/incidents?estado=pendiente&anio=2024&trimestre=1" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 📋 **RESPUESTAS ESPERADAS**

### **Analytics - Estadísticas Generales**
```json
{
  "status": "success",
  "data": {
    "usuarios": {
      "total": 10,
      "por_rol": {
        "director_administrativo": 1,
        "decano": 1,
        "docente": 4,
        "asistente_postgrado": 1,
        "técnico": 1,
        "auxiliar": 1
      },
      "este_mes": 2,
      "esta_semana": 1
    },
    "incidencias": {
      "total": 5,
      "por_estado": {
        "pendiente": 2,
        "resuelta": 3
      },
      "este_mes": 1,
      "pendientes": 2
    },
    "cursos": {
      "total": 20,
      "por_magister": [
        {
          "magister": "Magíster en Gestión de Sistemas de Salud",
          "count": 20
        }
      ]
    },
    "clases": {
      "total": 15,
      "por_modalidad": {
        "online": 8,
        "híbrida": 7
      }
    },
    "reportes_diarios": {
      "total": 3,
      "este_mes": 1,
      "esta_semana": 0
    },
    "novedades": {
      "total": 4,
      "urgentes": 2,
      "por_tipo": {
        "general": 2,
        "admision": 1,
        "academica": 1
      },
      "publicas": 4
    },
    "emergencias": {
      "total": 1,
      "activas": 0,
      "por_tipo": {
        "sistema": 1
      }
    },
    "staff": {
      "total": 12,
      "por_cargo": {
        "Decano": 1,
        "Director": 3,
        "Coordinadora": 2,
        "Docente": 4,
        "Personal de Apoyo": 2
      }
    }
  }
}
```

### **Error de Permisos**
```json
{
  "message": "No tienes permisos para crear miembros del equipo"
}
```

### **Error de Rol Inválido**
```json
{
  "message": "The rol field must be one of: director_administrativo, director_programa, asistente_programa, asistente_postgrado, docente, técnico, auxiliar, decano."
}
```

---

## 🚀 **CONFIGURACIÓN RÁPIDA**

### **1. Iniciar Servidor**
```bash
php artisan serve
```

### **2. Probar Endpoints Públicos**
Abre en el navegador:
- http://localhost:8000/api/public/courses
- http://localhost:8000/api/public/novedades
- http://localhost:8000/api/public/staff

### **3. Testing con Postman**
1. Importa la colección de la API
2. Configura variables de entorno
3. Ejecuta tests automáticos

---

## 📚 **DOCUMENTACIÓN ADICIONAL**

- **Cambios detallados**: `docs/API_ACTUALIZACION_COMPLETA.md`
- **Scripts de testing**: `docs/GUIA_TESTING_API.md`
- **Guía paso a paso**: `docs/COMO_TESTEAR_LA_API.md`

---

## ✅ **CHECKLIST DE VERIFICACIÓN**

- [x] Eliminación de referencias al rol `administrador`
- [x] Eliminación de referencias al rol `visor`
- [x] Validación de roles actualizada en AuthController
- [x] Restricciones de permisos en StaffController
- [x] Filtros mejorados en CourseController
- [x] Nuevo AnalyticsController creado
- [x] Middleware de roles específico aplicado
- [x] Documentación actualizada y consolidada

---

**Estado:** ✅ COMPLETADO Y ACTUALIZADO  
**Versión:** API v2.0  
**Fecha:** Diciembre 2024  
**Rutas públicas:** 14 endpoints  
**Rutas protegidas:** 50+ endpoints  
**Roles actualizados:** ✅ Sí  
**Filtros mejorados:** ✅ Sí
