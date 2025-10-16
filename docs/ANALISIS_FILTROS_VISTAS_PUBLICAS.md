# 🔍 ANÁLISIS COMPLETO DE FILTROS EN VISTAS PÚBLICAS

## 📅 Fecha: 15 de Octubre, 2025

---

## 📋 RESUMEN EJECUTIVO

Se identificaron **5 controladores públicos** que usan filtros y necesitan que los controladores API tengan los mismos filtros implementados.

---

## 🎯 CONTROLADORES PÚBLICOS ANALIZADOS

| # | Controlador | Vista | Filtros Usados | Estado API |
|---|-------------|-------|----------------|------------|
| 1 | PublicCourseController | courses.blade.php | `anio_ingreso` | ✅ YA CORREGIDO |
| 2 | PublicCalendarioController | calendario.blade.php | `anio_ingreso` | ⚠️ REVISAR |
| 3 | GuestEventController | (JSON) | `magister_id`, `room_id`, `anio_ingreso`, `start`, `end` | ⚠️ REVISAR |
| 4 | PublicInformeController | informes.blade.php | `search`, `tipo`, `magister_id`, `user_id` | ❌ FALTA |
| 5 | PublicDashboardController | novedades.blade.php | `search`, `tipo`, `magister_id` | ❌ FALTA |
| 6 | PublicStaffController | Equipo-FEN.blade.php | ✅ Sin filtros | ✅ OK |
| 7 | PublicRoomController | rooms.blade.php | ✅ Sin filtros | ✅ OK |
| 8 | PublicClaseController | show.blade.php | ✅ Solo show | ✅ OK |

---

## 🔧 DETALLE POR CONTROLADOR

### 1️⃣ **PublicCourseController** ✅ CORREGIDO

**Vista:** `resources/views/public/courses.blade.php`

**Filtros Usados:**
```php
$request->get('anio_ingreso', $aniosIngreso->first());
```

**Endpoint API:**
- `GET /api/public/courses`
- `GET /api/public/courses/magister/{id}`
- `GET /api/public/courses/magister/{id}/paginated`

**Estado:** ✅ **YA CORREGIDO** - Todos los métodos ahora soportan `anio_ingreso`

---

### 2️⃣ **PublicCalendarioController** ⚠️ NECESITA REVISIÓN

**Vista:** `resources/views/public/calendario.blade.php`

**Filtros Usados:**
```php
$anioIngresoSeleccionado = $request->get('anio_ingreso', $aniosIngreso->first());
$periodos = Period::where('anio_ingreso', $anioIngresoSeleccionado)->get();
```

**Endpoint API Relacionado:**
- `GET /api/public/events` (EventController::publicIndex)
- `GET /guest-events` (GuestEventController::index)

**Problema:** Necesita verificar si EventController::publicIndex soporta `anio_ingreso`

---

### 3️⃣ **GuestEventController** ⚠️ VERIFICAR

**Vista:** Llamado desde JavaScript en `calendario.blade.php`

**Filtros Usados:**
```php
- magister_id
- room_id
- anio_ingreso
- start (fecha inicio)
- end (fecha fin)
```

**Endpoint:**
```
GET /guest-events?magister_id=1&room_id=2&anio_ingreso=2024&start=2024-01-01&end=2024-12-31
```

**Estado:** ⚠️ Controlador PUBLIC ya tiene todos los filtros, pero necesita verificar que el API también los tenga

---

### 4️⃣ **PublicInformeController** ❌ FALTA EN API

**Vista:** `resources/views/public/informes.blade.php`

**Filtros Usados:**
```php
- search: Búsqueda en nombre y descripción
- tipo: Filtro por tipo de informe
- magister_id: Filtro por magíster
- user_id: Filtro por usuario
```

**Endpoints API:**
```
GET /api/public/informes
GET /api/public/informes/{id}
GET /api/public/informes/{id}/download
```

**Problema:** ❌ El API InformeController::publicIndex **NO tiene estos filtros**

**Solución Necesaria:**
```php
// InformeController::publicIndex() necesita:
if ($request->filled('search')) { ... }
if ($request->filled('tipo')) { ... }
if ($request->filled('magister_id')) { ... }
if ($request->filled('user_id')) { ... }
```

---

### 5️⃣ **PublicDashboardController::novedades** ❌ FALTA EN API

**Vista:** `resources/views/public/novedades.blade.php`

**Filtros Usados:**
```php
- search: Búsqueda en título y contenido
- tipo: Filtro por tipo de novedad
- magister_id: Filtro por magíster
```

**Endpoint API:**
```
GET /api/public/novedades
GET /api/public/novedades/{id}
```

**Problema:** ⚠️ El API NovedadController::active() **NO tiene estos filtros**

**Nota:** NovedadController::active() retorna todas las novedades activas sin filtros adicionales

**Solución Necesaria:**
```php
// NovedadController::active() o crear nuevo método publicIndex() con:
if ($request->filled('search')) { ... }
if ($request->filled('tipo')) { ... }
if ($request->filled('magister_id')) { ... }
```

---

## 🚨 PROBLEMAS CRÍTICOS IDENTIFICADOS

### ❌ **Problema 1: InformeController::publicIndex SIN FILTROS**

**Ubicación:** `app/Http/Controllers/Api/InformeController.php` línea 280

**Actual:**
```php
public function publicIndex(Request $request)
{
    try {
        $query = Informe::with(['user:id,name', 'magister:id,nombre']);

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // ❌ FALTAN: search, magister_id, user_id

        $perPage = $request->get('per_page', 15);
        $informes = $query->latest()->paginate($perPage);
        ...
    }
}
```

**Debe tener:**
```php
public function publicIndex(Request $request)
{
    try {
        $query = Informe::with(['user:id,name', 'magister:id,nombre']);

        // ✅ Filtro por búsqueda de texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        // ✅ Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // ✅ Filtro por magister
        if ($request->filled('magister_id')) {
            $query->where('magister_id', $request->magister_id);
        }

        // ✅ Filtro por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $perPage = $request->get('per_page', 15);
        $informes = $query->latest()->paginate($perPage);
        ...
    }
}
```

---

### ❌ **Problema 2: NovedadController::active SIN FILTROS**

**Ubicación:** `app/Http/Controllers/Api/NovedadController.php` línea 189

**Actual:**
```php
public function active()
{
    $novedades = Novedad::with(['user', 'magister'])
        ->where(function($q) {
            $q->whereNull('fecha_expiracion')
              ->orWhere('fecha_expiracion', '>', now());
        })
        ->latest()
        ->get();

    return response()->json([
        'success' => true,
        'data' => $novedades,
        'message' => 'Novedades activas obtenidas exitosamente'
    ]);
}
```

**Debe tener:**
```php
public function active(Request $request)
{
    $query = Novedad::with(['user', 'magister'])
        ->where(function($q) {
            $q->whereNull('fecha_expiracion')
              ->orWhere('fecha_expiracion', '>', now());
        });

    // ✅ Filtro por búsqueda de texto
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('titulo', 'like', "%{$search}%")
              ->orWhere('contenido', 'like', "%{$search}%");
        });
    }

    // ✅ Filtro por tipo
    if ($request->filled('tipo')) {
        $query->where('tipo', $request->tipo);
    }

    // ✅ Filtro por magíster
    if ($request->filled('magister_id')) {
        $query->where('magister_id', $request->magister_id);
    }

    $novedades = $query->latest()->get();

    return response()->json([
        'success' => true,
        'data' => $novedades,
        'message' => 'Novedades activas obtenidas exitosamente'
    ]);
}
```

---

### ⚠️ **Problema 3: EventController::publicIndex - Verificar filtros**

**Ubicación:** `app/Http/Controllers/Api/EventController.php` línea 388

**Filtros necesarios:**
- `magister_id` ✅ Ya tiene
- `room_id` ✅ Ya tiene
- `anio_ingreso` ❌ **FALTA**
- `start` ✅ Ya tiene
- `end` ✅ Ya tiene

**Solución:** Agregar filtro `anio_ingreso` para filtrar clases por año de ingreso

---

## 📊 TABLA COMPARATIVA DE FILTROS

| Filtro | Courses | Calendar | Events | Informes | Novedades |
|--------|---------|----------|--------|----------|-----------|
| `anio_ingreso` | ✅ | ✅ | ❌ | - | - |
| `magister_id` | - | - | ✅ | ❌ | ❌ |
| `room_id` | - | - | ✅ | - | - |
| `start` | - | - | ✅ | - | - |
| `end` | - | - | ✅ | - | - |
| `search` | - | - | - | ❌ | ❌ |
| `tipo` | - | - | - | ❌ | ❌ |
| `user_id` | - | - | - | ❌ | - |

**Leyenda:**
- ✅ Implementado
- ❌ Falta implementar
- - No aplica

---

## 🎯 PLAN DE ACCIÓN

### 🔴 **PRIORIDAD ALTA** (Bloqueante para funcionalidad)

1. **InformeController::publicIndex**
   - ✅ Agregar filtro `search`
   - ✅ Mantener filtro `tipo` (ya existe)
   - ✅ Agregar filtro `magister_id`
   - ✅ Agregar filtro `user_id`

2. **NovedadController::active**
   - ✅ Agregar parámetro `Request $request`
   - ✅ Agregar filtro `search`
   - ✅ Agregar filtro `tipo`
   - ✅ Agregar filtro `magister_id`

3. **EventController::publicIndex**
   - ✅ Agregar filtro `anio_ingreso` para clases

---

### 🟡 **PRIORIDAD MEDIA** (Mejoras)

4. **Consistencia de respuestas**
   - Verificar que todos los endpoints públicos retornen el mismo formato
   - Agregar metadata útil (filtros aplicados, totales, etc.)

5. **Documentación**
   - Documentar todos los filtros disponibles
   - Ejemplos de uso para cada endpoint

---

### 🟢 **PRIORIDAD BAJA** (Optimizaciones)

6. **Performance**
   - Cachear listas de magisters, tipos, etc.
   - Optimizar queries con índices

7. **Validación**
   - Validar parámetros de entrada
   - Mensajes de error descriptivos

---

## 🔧 CORRECCIONES NECESARIAS

### Archivo: `app/Http/Controllers/Api/InformeController.php`

**Método: publicIndex() - Líneas 280-336**

```php
// AGREGAR estos filtros después de la línea 286:

// Filtro por búsqueda de texto (NUEVO)
if ($request->filled('search')) {
    $search = $request->search;
    $query->where(function($q) use ($search) {
        $q->where('nombre', 'like', "%{$search}%")
          ->orWhere('descripcion', 'like', "%{$search}%");
    });
}

// Filtro por magister (NUEVO)
if ($request->filled('magister_id')) {
    $query->where('magister_id', $request->magister_id);
}

// Filtro por usuario (NUEVO)
if ($request->filled('user_id')) {
    $query->where('user_id', $request->user_id);
}
```

---

### Archivo: `app/Http/Controllers/Api/NovedadController.php`

**Método: active() - Líneas 189-204**

```php
// CAMBIAR firma del método:
public function active(Request $request) // Agregar Request

// AGREGAR filtros después de whereNull:

// Filtro por búsqueda de texto (NUEVO)
if ($request->filled('search')) {
    $search = $request->search;
    $query->where(function($q) use ($search) {
        $q->where('titulo', 'like', "%{$search}%")
          ->orWhere('contenido', 'like', "%{$search}%");
    });
}

// Filtro por tipo (NUEVO)
if ($request->filled('tipo')) {
    $query->where('tipo', $request->tipo);
}

// Filtro por magíster (NUEVO)
if ($request->filled('magister_id')) {
    $query->where('magister_id', $request->magister_id);
}
```

---

### Archivo: `app/Http/Controllers/Api/EventController.php`

**Método: publicIndex() - Línea 388**

**Agregar filtro anio_ingreso en generarEventosDesdeClasesOptimizado:**

```php
// En la línea 445, agregar $anioIngreso al método generarEventosDesdeClasesOptimizado:
$classEvents = $this->generarEventosDesdeClasesOptimizado(
    $magisterId, 
    $roomId, 
    $rangeStart, 
    $rangeEnd, 
    null, // ❌ Cambiar a: $request->query('anio_ingreso')
    25
);
```

---

## 📝 NOTAS IMPORTANTES

### Sobre InformeController

- El controlador WEB ya tiene todos los filtros implementados
- Solo falta copiarlos al método API `publicIndex()`
- La estructura de filtros es idéntica

### Sobre NovedadController

- El método `active()` actualmente no acepta filtros
- La vista pública espera poder filtrar por search, tipo y magister
- Necesita agregar `Request $request` como parámetro

### Sobre EventController

- El método `publicIndex()` ya filtra por magister y room
- Solo falta agregar soporte para `anio_ingreso`
- El método privado `generarEventosDesdeClasesOptimizado` ya soporta el filtro

---

## ✅ VALIDACIÓN POST-CORRECCIÓN

### Tests Recomendados

```bash
# Informes con filtros
curl "http://localhost:8000/api/public/informes?search=calendario"
curl "http://localhost:8000/api/public/informes?tipo=academico"
curl "http://localhost:8000/api/public/informes?magister_id=1"
curl "http://localhost:8000/api/public/informes?user_id=1"

# Novedades con filtros
curl "http://localhost:8000/api/public/novedades?search=evento"
curl "http://localhost:8000/api/public/novedades?tipo=academica"
curl "http://localhost:8000/api/public/novedades?magister_id=1"

# Eventos con anio_ingreso
curl "http://localhost:8000/api/public/events?anio_ingreso=2024&start=2024-01-01&end=2024-12-31"
```

---

## 🎓 CONCLUSIÓN

**Problemas Encontrados:** 3 controladores API sin filtros necesarios

**Impacto:** ALTO - Las vistas públicas no pueden filtrar correctamente los datos

**Solución:** Agregar filtros faltantes a 3 controladores:
1. ✅ InformeController::publicIndex
2. ✅ NovedadController::active
3. ✅ EventController::publicIndex

**Tiempo Estimado:** 30 minutos

**Estado:** ⚠️ **PENDIENTE DE IMPLEMENTACIÓN**

---

**Documento generado el 15/10/2025**
**Análisis completo de vistas públicas**

