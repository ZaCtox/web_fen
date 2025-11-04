# 🔐 Matriz Completa de Roles y Permisos - Sistema Web FEN

**Fecha:** 4 de noviembre de 2025  
**Estado:** ✅ ACTUALIZADO - Sincronizado Web + API

---

## 👥 Roles Activos del Sistema

| # | Rol | Descripción | Nivel de Acceso |
|---|-----|-------------|-----------------|
| 1 | `director_administrativo` | Director Administrativo | 🔴 **MÁXIMO** - Control total |
| 2 | `decano` | Decano | 🟡 **ALTO** - CRUD en mayoría de módulos |
| 3 | `director_programa` | Director de Programa | 🟢 **MEDIO** - Gestión académica |
| 4 | `asistente_programa` | Asistente de Programa | 🟢 **MEDIO** - Apoyo académico |
| 5 | `asistente_postgrado` | Asistente de Postgrado | 🟢 **MEDIO** - Gestión postgrado |
| 6 | `docente` | Docente/Profesor | 🔵 **BÁSICO** - Clases y calendario |
| 7 | `técnico` | Técnico de Soporte | 🔵 **BÁSICO** - Solo incidencias |
| 8 | `auxiliar` | Auxiliar de Soporte | 🔵 **BÁSICO** - Solo incidencias |

---

## 📊 Matriz Completa de Permisos por Módulo

### Leyenda:
- ✅ **CRUD** = Crear, Ver, Editar, Eliminar
- 👁️ **VER** = Solo lectura
- ❌ **NO** = Sin acceso

---

## 1️⃣ **USUARIOS** (`/usuarios`)

| Rol | Ver | Crear | Editar | Eliminar | API |
|-----|-----|-------|--------|----------|-----|
| `director_administrativo` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `decano` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `director_programa` | ❌ | ❌ | ❌ | ❌ | ❌ |
| `asistente_programa` | ❌ | ❌ | ❌ | ❌ | ❌ |
| `asistente_postgrado` | ❌ | ❌ | ❌ | ❌ | ❌ |
| `docente` | ❌ | ❌ | ❌ | ❌ | ❌ |
| `técnico` | ❌ | ❌ | ❌ | ❌ | ❌ |
| `auxiliar` | ❌ | ❌ | ❌ | ❌ | ❌ |

**Restricción especial:** No se puede eliminar el último `director_administrativo`

---

## 2️⃣ **STAFF / NUESTRO EQUIPO** (`/staff`)

| Rol | Ver | Crear | Editar | Eliminar | API |
|-----|-----|-------|--------|----------|-----|
| `director_administrativo` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `decano` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `director_programa` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `asistente_programa` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `asistente_postgrado` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `docente` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `técnico` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `auxiliar` | ✅ | ❌ | ❌ | ❌ | 👁️ |

---

## 3️⃣ **MAGISTERS / PROGRAMAS** (`/magisters`)

| Rol | Ver | Crear | Editar | Eliminar | API |
|-----|-----|-------|--------|----------|-----|
| `director_administrativo` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `decano` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `director_programa` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `asistente_programa` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `asistente_postgrado` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `docente` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `técnico` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `auxiliar` | ✅ | ❌ | ❌ | ❌ | 👁️ |

---

## 4️⃣ **COURSES / MÓDULOS** (`/courses`)

| Rol | Ver | Crear | Editar | Eliminar | API |
|-----|-----|-------|--------|----------|-----|
| `director_administrativo` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `decano` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `director_programa` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `asistente_programa` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `asistente_postgrado` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `docente` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `técnico` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `auxiliar` | ✅ | ❌ | ❌ | ❌ | 👁️ |

---

## 5️⃣ **CLASES** (`/clases`)

| Rol | Ver | Crear | Editar | Eliminar | API |
|-----|-----|-------|--------|----------|-----|
| `director_administrativo` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `decano` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `director_programa` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `asistente_programa` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `asistente_postgrado` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `docente` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `técnico` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `auxiliar` | ✅ | ❌ | ❌ | ❌ | 👁️ |

**Nota:** `docente` puede ver el calendario con sus clases

---

## 6️⃣ **PERIODS / PERÍODOS** (`/periods`)

| Rol | Ver | Crear | Editar | Eliminar | API |
|-----|-----|-------|--------|----------|-----|
| `director_administrativo` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `decano` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `director_programa` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `asistente_programa` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `asistente_postgrado` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `docente` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `técnico` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `auxiliar` | ✅ | ❌ | ❌ | ❌ | 👁️ |

---

## 7️⃣ **ROOMS / SALAS** (`/rooms`)

| Rol | Ver | Crear | Editar | Eliminar | API |
|-----|-----|-------|--------|----------|-----|
| `director_administrativo` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `decano` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `director_programa` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `asistente_programa` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `asistente_postgrado` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `docente` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `técnico` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `auxiliar` | ✅ | ❌ | ❌ | ❌ | 👁️ |

---

## 8️⃣ **INCIDENTS / INCIDENCIAS** (`/incidencias`)

| Rol | Ver | Crear | Editar | Eliminar | API |
|-----|-----|-------|--------|----------|-----|
| `director_administrativo` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `decano` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `director_programa` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `asistente_programa` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `asistente_postgrado` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `docente` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `técnico` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `auxiliar` | ✅ | ✅ | ✅ | ✅ | ✅ |

**Nota:** Todos pueden ver incidencias, pero solo los roles con permiso pueden modificar

---

## 9️⃣ **INFORMES / ARCHIVOS** (`/informes`)

| Rol | Ver | Crear | Editar | Eliminar | API |
|-----|-----|-------|--------|----------|-----|
| `director_administrativo` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `decano` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `director_programa` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `asistente_programa` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `asistente_postgrado` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `docente` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `técnico` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `auxiliar` | ✅ | ❌ | ❌ | ❌ | 👁️ |

---

## 🔟 **EMERGENCIES / EMERGENCIAS** (`/emergencies`)

| Rol | Ver | Crear | Editar | Eliminar | API |
|-----|-----|-------|--------|----------|-----|
| `director_administrativo` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `decano` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `director_programa` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `asistente_programa` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `asistente_postgrado` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `docente` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `técnico` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `auxiliar` | ✅ | ❌ | ❌ | ❌ | 👁️ |

---

## 1️⃣1️⃣ **EVENTS / EVENTOS DEL CALENDARIO** (`/eventos` + `/calendario`)

| Rol | Ver | Crear | Editar | Eliminar | API |
|-----|-----|-------|--------|----------|-----|
| `director_administrativo` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `decano` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `director_programa` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `asistente_programa` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `asistente_postgrado` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `docente` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `técnico` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `auxiliar` | ✅ | ❌ | ❌ | ❌ | 👁️ |

**Nota:** `docente` puede crear eventos en el calendario

---

## 1️⃣2️⃣ **NOVEDADES** (`/novedades`)

| Rol | Ver | Crear | Editar | Eliminar | API |
|-----|-----|-------|--------|----------|-----|
| `director_administrativo` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `decano` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `director_programa` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `asistente_programa` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `asistente_postgrado` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `docente` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `técnico` | ✅ | ❌ | ❌ | ❌ | 👁️ |
| `auxiliar` | ✅ | ❌ | ❌ | ❌ | 👁️ |

---

## 1️⃣3️⃣ **DAILY REPORTS / REPORTES DIARIOS** (`/daily-reports`)

| Rol | Ver | Crear | Editar | Eliminar | API |
|-----|-----|-------|--------|----------|-----|
| `director_administrativo` | ❌ | ❌ | ❌ | ❌ | ❌ |
| `decano` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `director_programa` | ❌ | ❌ | ❌ | ❌ | ❌ |
| `asistente_programa` | ❌ | ❌ | ❌ | ❌ | ❌ |
| `asistente_postgrado` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `docente` | ❌ | ❌ | ❌ | ❌ | ❌ |
| `técnico` | ❌ | ❌ | ❌ | ❌ | ❌ |
| `auxiliar` | ❌ | ❌ | ❌ | ❌ | ❌ |

**Nota:** Solo `decano` y `asistente_postgrado` tienen acceso

---

## 1️⃣4️⃣ **ANALYTICS / ESTADÍSTICAS** (`/analytics`)

| Rol | Ver | Crear | Editar | Eliminar | API |
|-----|-----|-------|--------|----------|-----|
| `director_administrativo` | ✅ | ❌ | ❌ | ❌ | ✅ |
| `decano` | ✅ | ❌ | ❌ | ❌ | ✅ |
| `director_programa` | ✅ | ❌ | ❌ | ❌ | ✅ |
| `asistente_programa` | ❌ | ❌ | ❌ | ❌ | ❌ |
| `asistente_postgrado` | ✅ | ❌ | ❌ | ❌ | ✅ |
| `docente` | ❌ | ❌ | ❌ | ❌ | ❌ |
| `técnico` | ❌ | ❌ | ❌ | ❌ | ❌ |
| `auxiliar` | ❌ | ❌ | ❌ | ❌ | ❌ |

---

## 📌 Resumen de Acceso por Rol

### 🔴 **Director Administrativo** - MÁXIMO
- ✅ **14/14 módulos** con CRUD completo
- ❌ **Excepción:** No tiene acceso a Daily Reports (módulo específico de postgrado)

### 🟡 **Decano** - ALTO  
- ✅ **14/14 módulos** con CRUD completo
- ✅ Incluye Daily Reports

### 🟢 **Director de Programa** - MEDIO
- ✅ **10/14 módulos** con acceso (7 CRUD + 3 solo lectura)
- ✅ CRUD: Courses, Clases, Incidents, Informes, Emergencies, Events
- 👁️ Solo lectura: Staff, Magisters, Periods, Rooms, Novedades
- ❌ Sin acceso: Usuarios, Daily Reports, Analytics

### 🟢 **Asistente de Programa** - MEDIO
- ✅ **11/14 módulos** con acceso (6 CRUD + 5 solo lectura)
- ✅ CRUD: Courses, Clases, Rooms, Incidents, Informes, Emergencies, Events
- 👁️ Solo lectura: Staff, Magisters, Periods, Novedades
- ❌ Sin acceso: Usuarios, Daily Reports, Analytics

### 🟢 **Asistente de Postgrado** - MEDIO
- ✅ **12/14 módulos** con acceso (6 CRUD + 6 solo lectura)
- ✅ CRUD: Clases, Incidents, Informes, Emergencies, Events, Novedades, Daily Reports
- 👁️ Solo lectura: Staff, Magisters, Courses, Periods, Rooms
- ❌ Sin acceso: Usuarios
- ✅ Acceso a Analytics

### 🔵 **Docente** - BÁSICO
- ✅ **11/14 módulos** solo lectura + calendario con CRUD
- ✅ CRUD: Events (solo calendario)
- 👁️ Solo lectura: Staff, Magisters, Courses, Clases, Periods, Rooms, Incidents, Informes, Emergencies, Novedades
- ❌ Sin acceso: Usuarios, Daily Reports, Analytics

### 🔵 **Técnico** - BÁSICO  
- ✅ **10/14 módulos** con acceso (1 CRUD + 9 solo lectura)
- ✅ CRUD: Incidents (solo técnicas)
- 👁️ Solo lectura: Staff, Magisters, Courses, Clases, Periods, Rooms, Informes, Emergencies, Novedades
- ❌ Sin acceso: Usuarios, Events, Daily Reports, Analytics

### 🔵 **Auxiliar** - BÁSICO
- ✅ **10/14 módulos** con acceso (1 CRUD + 9 solo lectura)
- ✅ CRUD: Incidents (solo básicas)
- 👁️ Solo lectura: Staff, Magisters, Courses, Clases, Periods, Rooms, Informes, Emergencies, Novedades
- ❌ Sin acceso: Usuarios, Events, Daily Reports, Analytics

---

## ✅ Estado de Sincronización

- ✅ **Web (Blade Templates)**: Permisos actualizados
- ✅ **API (routes/api.php)**: Middlewares aplicados correctamente
- ✅ **Controladores API**: Validaciones eliminadas (usan middleware)
- ✅ **Roles obsoletos eliminados**: `administrador`, `visor`

---

## 📝 Notas Importantes

1. **Todos los usuarios autenticados** pueden VER (GET) la mayoría de los recursos
2. **Los permisos de escritura** (POST/PUT/DELETE) están restringidos por rol
3. **La API pública** (`/api/public/*`) no requiere autenticación
4. **Daily Reports** es un módulo especial solo para `decano` y `asistente_postgrado`
5. **Analytics** es solo lectura para `director_administrativo`, `decano`, `director_programa`, `asistente_postgrado`

---

**Última actualización:** 4 de noviembre de 2025

