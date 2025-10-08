# Sistema de Gestión de Novedades - Web FEN

## 🎯 Descripción General

El sistema de novedades permite a los administradores crear, editar y gestionar contenido informativo que se muestra tanto en el sitio público como en el dashboard interno, segmentado por roles.

---

## 👥 Quién Gestiona las Novedades

### Roles con Acceso Completo:
- ✅ **Administrador** - Control total del sistema
- ✅ **Director Administrativo** - Gestión de novedades institucionales
- ✅ **Asistente de Postgrado** - Publicación de novedades académicas

### Permisos:
- ✅ Crear novedades
- ✅ Editar novedades
- ✅ Eliminar novedades
- ✅ Duplicar novedades (para crear versiones similares)
- ✅ Gestionar visibilidad (pública/privada/por roles)
- ✅ Marcar como urgente
- ✅ Establecer fecha de expiración
- ✅ Asociar a programas de magíster

---

## 📍 Rutas Configuradas

### Rutas Administrativas (Protegidas):

```php
// CRUD Completo
GET    /novedades              → Listado
GET    /novedades/create       → Formulario de creación
POST   /novedades              → Guardar nueva
GET    /novedades/{id}         → Ver detalle
GET    /novedades/{id}/edit    → Editar
PUT    /novedades/{id}         → Actualizar
DELETE /novedades/{id}         → Eliminar

// Acción especial
POST   /novedades/{id}/duplicate → Duplicar novedad
```

### Rutas Públicas (Sin autenticación):

```php
// Dashboard público
GET    /                       → Muestra novedades públicas

// Futuras rutas sugeridas:
GET    /novedades              → Listado público completo
GET    /novedades/{id}         → Detalle de novedad pública
```

---

## 🎨 Controladores

### 1. **NovedadController** (Administrativo)
**Ubicación:** `app/Http/Controllers/NovedadController.php`

**Métodos disponibles:**

#### `index()` - Listado Administrativo
- Muestra todas las novedades del sistema
- Filtros disponibles:
  - Por tipo (académica, evento, etc.)
  - Por estado (activa/expirada)
  - Por visibilidad (pública/privada)
  - Búsqueda por título/contenido
- Paginación de 15 por página
- Con información de magíster y usuario creador

#### `create()` - Formulario de Creación
- Carga lista de magísteres
- 9 tipos de novedades disponibles
- 8 roles para segmentación
- Campos para personalización (color, icono)

#### `store()` - Guardar Nueva
- Validación completa de datos
- Asigna automáticamente el usuario creador
- Si es pública, limpia roles específicos
- Logging de la acción

#### `show()` - Ver Detalle
- Muestra toda la información
- Carga relaciones (magíster, usuario)

#### `edit()` - Editar Formulario
- Similar a create pero con datos precargados

#### `update()` - Actualizar
- Validación y actualización
- Logging de cambios
- Control de visibilidad

#### `destroy()` - Eliminar
- Eliminación con logging
- Mensaje de confirmación

#### `duplicate()` - Duplicar ⭐
- Crea una copia de una novedad existente
- Agrega "(Copia)" al título
- Útil para novedades recurrentes

---

### 2. **PublicDashboardController**
**Ubicación:** `app/Http/Controllers/PublicSite/PublicDashboardController.php`

**Métodos:**

#### `index()` - Dashboard Público
- Muestra 8 novedades públicas activas
- Destaca 3 novedades urgentes
- Ordena por urgencia y fecha
- Con manejo de errores robusto

#### `novedades()` - Listado Público
- Paginación de 12 por página
- Filtros por tipo y magíster
- Solo novedades públicas y activas

#### `novedadDetalle()` - Detalle Público
- Vista completa de una novedad
- Valida que sea pública y no expirada
- Muestra 3 novedades relacionadas

---

## 📊 Modelo Novedad

**Ubicación:** `app/Models/Novedad.php`

### Campos Principales:

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `titulo` | string | Título de la novedad |
| `contenido` | text | Descripción completa |
| `tipo_novedad` | string | Categoría (académica, evento, etc.) |
| `visible_publico` | boolean | Si es visible sin login |
| `es_urgente` | boolean | Marca como importante |
| `color` | string | Color de identificación |
| `icono` | string | Emoji representativo |
| `fecha_expiracion` | datetime | Fecha límite de visualización |
| `roles_visibles` | json | Array de roles que pueden verla |
| `magister_id` | integer | Asociación a programa |
| `user_id` | integer | Quién la creó |

### Scopes Útiles:

```php
// Solo novedades no expiradas
Novedad::activas()->get();

// Solo novedades urgentes
Novedad::urgentes()->get();

// Por tipo específico
Novedad::porTipo('evento')->get();

// Para un rol específico
Novedad::paraRol('docente')->get();
```

### Métodos Útiles:

```php
// Verificar si un rol puede verla
$novedad->esVisibleParaRol('docente');

// Verificar si está expirada
$novedad->estaExpirada();

// Crear novedad automática
Novedad::crearAutomatica(
    'Título',
    'Contenido',
    ['docente', 'administrador'],
    ['color' => 'blue', 'urgente' => true]
);
```

---

## 🎨 Tipos de Novedades

| Tipo | Uso Recomendado | Color Sugerido | Icono |
|------|-----------------|----------------|-------|
| **academica** | Información académica general | Azul | 🎓 |
| **evento** | Seminarios, charlas, conferencias | Verde/Azul | 🌐 |
| **admision** | Procesos de admisión y postulación | Rojo | 📝 |
| **institucional** | Logros, acreditaciones, noticias FEN | Amarillo | 🏆 |
| **administrativa** | Reuniones, coordinaciones internas | Rojo | 👥 |
| **sistema** | Cambios en plataformas, sistemas | Amarillo | 💻 |
| **oportunidad** | Pasantías, becas, convocatorias | Verde | 🎯 |
| **servicio** | Horarios, atención, servicios | Azul | 🏢 |
| **mantenimiento** | Mantenciones programadas | Naranja | ⚡ |

---

## 💡 Ejemplos de Uso

### Crear Novedad Pública:
1. Login como administrador
2. Ir a `/novedades`
3. Click en "Crear Novedad"
4. Llenar formulario:
   - Título y contenido
   - Tipo de novedad
   - Marcar "Visible al público" ✅
   - Seleccionar color e icono
   - (Opcional) Fecha de expiración
5. Guardar

### Crear Novedad para Docentes:
1. Mismo proceso pero:
   - NO marcar "Visible al público" ❌
   - En "Roles visibles" seleccionar "Docente"
   - La novedad solo la verán usuarios con rol docente

### Duplicar Novedad:
1. En el listado, buscar la novedad
2. Click en "Duplicar"
3. Te lleva al formulario de edición con los datos copiados
4. Modificar lo necesario y guardar

---

## 🚀 Flujo Completo

### Para Administradores:

```
1. Login → Dashboard
2. Menú → Novedades
3. Ver listado con todas las novedades
4. Crear/Editar/Eliminar según necesidad
5. Las novedades públicas aparecen automáticamente en el sitio público
6. Las novedades por rol se muestran en el dashboard interno
```

### Para Visitantes del Sitio:

```
1. Entrar al sitio público (sin login)
2. Ver sección "Anuncios Importantes" (novedades urgentes)
3. Ver sección "Novedades y Actividades" (grid de tarjetas)
4. Click en "Ver más" para futuro detalle
```

### Para Usuarios Logueados:

```
1. Login al sistema
2. En el dashboard ver novedades según su rol
3. Novedades públicas + Novedades de su rol específico
```

---

## 📂 Archivos del Sistema

### Controladores:
- ✅ `app/Http/Controllers/NovedadController.php`
- ✅ `app/Http/Controllers/PublicSite/PublicDashboardController.php`

### Modelos:
- ✅ `app/Models/Novedad.php`

### Seeders:
- ✅ `database/seeders/NovedadesSeeder.php`
- ✅ Integrado en `database/seeders/DatabaseSeeder.php`

### Rutas:
- ✅ `routes/web.php` (administrativas)
- ⏳ `routes/public.php` (pendiente: rutas públicas adicionales)

### Vistas:
- ✅ `resources/views/public/dashboard.blade.php` (actualizada)
- ⏳ `resources/views/novedades/index.blade.php` (pendiente)
- ⏳ `resources/views/novedades/create.blade.php` (pendiente)
- ⏳ `resources/views/novedades/edit.blade.php` (pendiente)
- ⏳ `resources/views/novedades/show.blade.php` (pendiente)

---

## ✅ Estado Actual

### Implementado:
- ✅ Modelo Novedad con scopes y métodos
- ✅ NovedadController completo (CRUD + Duplicate)
- ✅ PublicDashboardController con novedades
- ✅ Rutas administrativas configuradas
- ✅ Seeder con 16 novedades realistas
- ✅ Vista pública actualizada con novedades
- ✅ Sistema de colores, iconos y tipos
- ✅ Control de visibilidad por roles
- ✅ Fechas de expiración automáticas
- ✅ Logging completo de acciones

### Pendiente:
- ⏳ Vistas Blade administrativas (index, create, edit, show)
- ⏳ Rutas públicas adicionales
- ⏳ Vista detalle público de novedad
- ⏳ Subida de imágenes para novedades
- ⏳ Editor rich text para contenido
- ⏳ Sistema de notificaciones por nuevas novedades

---

## 🔒 Seguridad

### Middleware Aplicado:
```php
->middleware('role:administrador,director_administrativo,asistente_postgrado')
```

### Validaciones:
- ✅ Título requerido (max 255 caracteres)
- ✅ Contenido requerido
- ✅ Tipo de novedad validado contra lista
- ✅ Magíster debe existir si se especifica
- ✅ Fecha de expiración debe ser futura
- ✅ Roles visibles debe ser array válido

### Logging:
- ✅ Creación de novedades
- ✅ Actualización de novedades
- ✅ Eliminación de novedades
- ✅ Duplicación de novedades
- ✅ Errores en operaciones

---

## 📊 Datos del Seeder

Se crean **16 novedades** de ejemplo:
- 10 públicas (visibles sin login)
- 6 privadas (solo para roles específicos)
- 8 marcadas como urgentes
- 13 con fecha de expiración
- 4 asociadas a magísteres específicos

---

**Fecha de implementación:** Octubre 2025  
**Desarrollador:** AI Assistant  
**Versión:** 1.0  
**Estado:** ✅ Backend Completo - Vistas Pendientes

