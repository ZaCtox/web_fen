# 📊 Sistema de Analytics y Estadísticas

## Resumen

Se ha implementado un sistema completo de analytics para trackear accesos a la plataforma y calcular métricas clave del sistema FEN.

## 🎯 Métricas Implementadas

### 1. ⏱️ Tiempo Promedio de Respuesta a Incidencias
**Descripción:** Calcula el tiempo promedio (en horas) que toma resolver una incidencia desde su creación hasta su resolución.

**Ubicación:** Ya existía en `/incidencias/estadisticas`

**Cálculo:** 
```php
$tiempoPromedioIncidencias = Incident::where('estado', 'resuelta')
    ->whereNotNull('resuelta_en')
    ->avg(function($inc) {
        return $inc->created_at->diffInHours($inc->resuelta_en);
    });
```

**Uso:** Esta métrica permite evaluar la eficiencia del equipo de soporte en resolver problemas.

---

### 2. 📅 Porcentaje de Utilización del Calendario Académico
**Descripción:** Mide qué porcentaje de los días hábiles del calendario académico tienen clases o eventos programados.

**Ubicación:** `/analytics`

**Cálculo:**
- Se cuentan los días hábiles (lunes a viernes) dentro de los períodos académicos
- Se cuentan los días únicos con al menos una sesión de clase programada (usando `ClaseSesion`)
- Porcentaje = (días con clases / total días hábiles) × 100

**Nota técnica:** Se usa el modelo `ClaseSesion` en lugar de `Event` porque las sesiones contienen las fechas reales de las clases.

**Uso:** Permite evaluar cuán eficientemente se está usando el calendario académico y detectar períodos subutilizados.

---

### 3. 👥 Número de Accesos a la Plataforma
**Descripción:** Registra y contabiliza todos los accesos a páginas clave de la plataforma, diferenciando entre usuarios registrados y anónimos.

**Ubicación:** `/analytics`

**Métricas incluidas:**
- **Accesos totales mensuales**
- **Sesiones únicas** (usuarios distintos)
- **Accesos al calendario público** (visitantes sin login)
- **Accesos al calendario administrativo** (usuarios autenticados)
- **Top páginas más visitadas**
- **Distribución usuarios registrados vs anónimos**
- **Tendencia mensual de accesos**

---

## 🗄️ Estructura de Base de Datos

### Tabla: `page_views`

```sql
CREATE TABLE page_views (
    id BIGINT PRIMARY KEY,
    user_id BIGINT NULL,              -- null = visitante anónimo
    page_type VARCHAR(50),             -- tipo de página
    url VARCHAR(500),                  -- URL completa
    method VARCHAR(10),                -- GET, POST, etc.
    ip_address VARCHAR(45),            -- IP del visitante
    user_agent VARCHAR(500),           -- navegador/dispositivo
    session_id VARCHAR(255),           -- para sesiones únicas
    visited_at TIMESTAMP,              -- fecha/hora del acceso
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX (page_type, visited_at),
    INDEX (user_id, visited_at),
    INDEX (visited_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

## 🔧 Componentes del Sistema

### 1. Modelo: `PageView`
**Ubicación:** `app/Models/PageView.php`

**Relaciones:**
- `belongsTo(User::class)` - relación con usuario (nullable)

**Scopes útiles:**
```php
PageView::calendarioPublico()  // Solo accesos al calendario público
PageView::calendarioAdmin()    // Solo accesos al calendario admin
PageView::dashboard()          // Solo accesos al dashboard
PageView::thisMonth()          // Accesos del mes actual
PageView::thisYear()           // Accesos del año actual
PageView::betweenDates($start, $end)  // Accesos en rango
```

**Método estático:**
```php
PageView::countUniqueSessions($pageType, $startDate, $endDate)
```

---

### 2. Middleware: `TrackPageViews`
**Ubicación:** `app/Http/Middleware/TrackPageViews.php`

**Funcionamiento:**
- Se ejecuta automáticamente en todas las peticiones web (registrado globalmente)
- Solo trackea peticiones GET exitosas
- Solo registra páginas específicas definidas en `$trackedPages`
- Captura: usuario, tipo de página, URL, IP, user agent, session ID

**Páginas trackeadas:**
```php
protected $trackedPages = [
    // Páginas públicas (sin login)
    'public.dashboard.index' => 'inicio_publico',
    'public.calendario.index' => 'calendario_publico',
    'public.Equipo-FEN.index' => 'equipo_publico',
    'public.rooms.index' => 'salas_publico',
    'public.courses.index' => 'cursos_publico',
    'public.informes.index' => 'archivos_publico',
    
    // Páginas con sesión (autenticadas)
    'dashboard' => 'dashboard_autenticado',
    'calendario' => 'calendario_autenticado',
    'incidencias.index' => 'incidencias_autenticado',
];
```

**Agregar nuevas páginas a trackear:**
Solo agrega una entrada al array `$trackedPages` con:
- **Clave:** nombre de la ruta
- **Valor:** identificador descriptivo del tipo de página

---

### 3. Controlador: `AnalyticsController`
**Ubicación:** `app/Http/Controllers/AnalyticsController.php`

**Métodos principales:**

#### `index(Request $request)`
Muestra el dashboard de estadísticas con:
- Filtros por mes, año y año de ingreso
- KPIs principales (tiempo respuesta, utilización calendario, accesos)
- Gráficos de tendencias
- Tablas de páginas más visitadas

#### `api(Request $request)`
Endpoint JSON para obtener estadísticas programáticamente:
```
GET /analytics/api?mes=10&anio=2024
```

Retorna:
```json
{
    "tiempo_promedio_incidencias": 24.5,
    "porcentaje_utilizacion_calendario": 78.3,
    "accesos_mensuales": 1523,
    "accesos_por_tipo": [...]
}
```

---

### 4. Vista: `analytics/index.blade.php`
**Ubicación:** `resources/views/analytics/index.blade.php`

**Características:**
- 📊 KPIs principales en tarjetas destacadas
- 📈 Gráfico de tendencias mensuales (Chart.js)
- 📅 Comparación calendario público vs administrativo
- 📋 Tabla de top páginas visitadas
- 👤 Distribución usuarios registrados vs anónimos
- 🎨 Diseño responsive con modo oscuro

---

## 🚀 Rutas Implementadas

```php
// Dashboard de estadísticas
GET /analytics
    -> AnalyticsController@index
    -> middleware: auth, role:administrador,director_administrativo,director_programa,asistente_postgrado

// API de estadísticas
GET /analytics/api
    -> AnalyticsController@api
    -> middleware: auth, role:administrador,director_administrativo,director_programa,asistente_postgrado
```

---

## 🎯 Acceso al Sistema

### Navegación Web
1. Iniciar sesión como usuario con rol permitido
2. Ir a menú **Administración** → **📊 Estadísticas**

### Roles con acceso
- ✅ Administrador
- ✅ Director Administrativo
- ✅ Director de Programa
- ✅ Asistente de Postgrado

---

## 📊 Uso de las Estadísticas

### Dashboard Principal (`/analytics`)

**Filtros disponibles:**
- **Mes:** Filtra datos del mes específico
- **Año:** Filtra datos del año específico
- **Año de Ingreso:** Filtra períodos académicos por cohorte

**Secciones:**

1. **KPIs Principales (tarjetas superiores)**
   - Tiempo promedio de respuesta a incidencias
   - Porcentaje de utilización del calendario
   - Número de accesos mensuales

2. **Accesos al Calendario**
   - Comparación público vs administrativo
   - Útil para evaluar engagement de usuarios no registrados

3. **Top Páginas Más Visitadas**
   - Tabla con páginas ordenadas por popularidad
   - Muestra cantidad y porcentaje de visitas

4. **Tipo de Usuarios**
   - Usuarios registrados (autenticados)
   - Visitantes anónimos (públicos)

5. **Gráfico de Tendencias**
   - Accesos mensuales del año completo
   - Permite identificar patrones estacionales

---

## 🛠️ Mantenimiento

### Limpiar datos antiguos
Por rendimiento, se recomienda limpiar periódicamente los accesos antiguos:

```php
// Eliminar accesos de hace más de 1 año
PageView::where('visited_at', '<', now()->subYear())->delete();
```

Puedes crear un comando programado:
```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        PageView::where('visited_at', '<', now()->subYear())->delete();
    })->monthly();
}
```

### Optimización de índices
La tabla ya tiene índices optimizados para búsquedas rápidas:
- `(page_type, visited_at)` - para filtros por tipo
- `(user_id, visited_at)` - para análisis por usuario
- `(visited_at)` - para rangos de fechas

---

## 🔍 Consultas Útiles

### Accesos del día de hoy
```php
$today = PageView::whereDate('visited_at', today())->count();
```

### Páginas más visitadas este mes
```php
$top = PageView::thisMonth()
    ->select('page_type', DB::raw('count(*) as total'))
    ->groupBy('page_type')
    ->orderByDesc('total')
    ->limit(10)
    ->get();
```

### Usuarios más activos
```php
$activeUsers = PageView::whereNotNull('user_id')
    ->thisMonth()
    ->select('user_id', DB::raw('count(*) as visitas'))
    ->groupBy('user_id')
    ->orderByDesc('visitas')
    ->with('user')
    ->limit(10)
    ->get();
```

### Horas pico de acceso
```php
$hourly = PageView::thisMonth()
    ->select(DB::raw('HOUR(visited_at) as hora'), DB::raw('count(*) as total'))
    ->groupBy('hora')
    ->orderBy('hora')
    ->get();
```

---

## 🚨 Consideraciones Importantes

### Privacidad
- ✅ Solo se registra información técnica necesaria
- ✅ IPs se almacenan de forma segura
- ✅ No se registran datos sensibles
- ⚠️ Cumplir con políticas de privacidad locales

### Rendimiento
- ✅ Tracking es asíncrono, no afecta experiencia del usuario
- ✅ Si falla el tracking, no interrumpe la petición
- ✅ Índices optimizados para búsquedas rápidas
- ⚠️ Considerar archivar datos antiguos si crecimiento es muy rápido

### Exclusiones
El middleware **NO** trackea:
- ❌ Peticiones POST, PUT, DELETE (solo GET)
- ❌ Peticiones fallidas (errores 4xx, 5xx)
- ❌ Rutas no definidas en `$trackedPages`
- ❌ APIs (solo rutas web)
- ❌ Assets estáticos (CSS, JS, imágenes)

---

## 📚 Extensiones Futuras

### Posibles mejoras:
1. **Dashboard en tiempo real** con WebSockets
2. **Alertas automáticas** cuando métricas bajan de umbrales
3. **Exportación de reportes** en PDF/Excel
4. **Análisis de navegación** (rutas más comunes)
5. **Métricas de dispositivos** (móvil vs desktop)
6. **Tiempo de permanencia** en cada página
7. **Tasas de conversión** (visitantes anónimos → registrados)
8. **Comparativas año anterior** (YoY)

---

## 📞 Soporte

Para dudas o problemas con el sistema de analytics:
- Revisar logs en `storage/logs/laravel.log`
- Verificar que la migración se ejecutó correctamente
- Confirmar que el middleware está registrado en `bootstrap/app.php`
- Verificar permisos de usuario para acceder a `/analytics`

---

## ✅ Checklist de Implementación

- [x] Migración `create_page_views_table`
- [x] Modelo `PageView`
- [x] Middleware `TrackPageViews`
- [x] Controlador `AnalyticsController`
- [x] Vista `analytics/index.blade.php`
- [x] Rutas en `web.php`
- [x] Enlace en navegación
- [x] Middleware registrado globalmente
- [x] Documentación completa

---

## 📖 Referencias

- [Laravel Middleware](https://laravel.com/docs/middleware)
- [Chart.js Documentation](https://www.chartjs.org/)
- [Laravel Query Builder](https://laravel.com/docs/queries)

