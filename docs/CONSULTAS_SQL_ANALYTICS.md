# 📊 Consultas SQL Útiles - Sistema de Analytics

## Verificación Básica

### Contar registros totales
```sql
SELECT COUNT(*) as total_accesos 
FROM page_views;
```

### Ver últimos 10 accesos
```sql
SELECT 
    id,
    page_type,
    user_id,
    url,
    visited_at
FROM page_views 
ORDER BY visited_at DESC 
LIMIT 10;
```

---

## Análisis de Accesos

### Accesos por tipo de página
```sql
SELECT 
    page_type,
    COUNT(*) as total,
    COUNT(DISTINCT session_id) as sesiones_unicas,
    COUNT(DISTINCT user_id) as usuarios_unicos
FROM page_views 
GROUP BY page_type 
ORDER BY total DESC;
```

### Accesos por día (último mes)
```sql
SELECT 
    DATE(visited_at) as fecha,
    COUNT(*) as total_accesos,
    COUNT(DISTINCT session_id) as sesiones_unicas
FROM page_views 
WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY DATE(visited_at) 
ORDER BY fecha DESC;
```

### Accesos por hora del día
```sql
SELECT 
    HOUR(visited_at) as hora,
    COUNT(*) as total_accesos
FROM page_views 
WHERE DATE(visited_at) = CURDATE()
GROUP BY HOUR(visited_at) 
ORDER BY hora;
```

---

## Calendario Académico

### Comparar calendario público vs admin (mes actual)
```sql
SELECT 
    page_type,
    COUNT(*) as accesos,
    COUNT(DISTINCT session_id) as sesiones_unicas
FROM page_views 
WHERE page_type IN ('calendario_publico', 'calendario_admin')
  AND MONTH(visited_at) = MONTH(NOW())
  AND YEAR(visited_at) = YEAR(NOW())
GROUP BY page_type;
```

### Accesos diarios al calendario público (último mes)
```sql
SELECT 
    DATE(visited_at) as fecha,
    COUNT(*) as accesos
FROM page_views 
WHERE page_type = 'calendario_publico'
  AND visited_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY DATE(visited_at) 
ORDER BY fecha;
```

---

## Usuarios

### Usuarios registrados vs anónimos (mes actual)
```sql
SELECT 
    CASE 
        WHEN user_id IS NULL THEN 'Anonimo'
        ELSE 'Registrado'
    END as tipo_usuario,
    COUNT(*) as total_accesos,
    COUNT(DISTINCT session_id) as sesiones
FROM page_views 
WHERE MONTH(visited_at) = MONTH(NOW())
  AND YEAR(visited_at) = YEAR(NOW())
GROUP BY tipo_usuario;
```

### Top 10 usuarios más activos
```sql
SELECT 
    pv.user_id,
    u.nombre,
    u.email,
    COUNT(*) as total_accesos,
    MAX(pv.visited_at) as ultimo_acceso
FROM page_views pv
INNER JOIN users u ON pv.user_id = u.id
WHERE pv.user_id IS NOT NULL
GROUP BY pv.user_id, u.nombre, u.email
ORDER BY total_accesos DESC
LIMIT 10;
```

---

## Tendencias Mensuales

### Accesos por mes (año actual)
```sql
SELECT 
    MONTH(visited_at) as mes,
    MONTHNAME(visited_at) as nombre_mes,
    COUNT(*) as total_accesos,
    COUNT(DISTINCT session_id) as sesiones_unicas
FROM page_views 
WHERE YEAR(visited_at) = YEAR(NOW())
GROUP BY MONTH(visited_at), MONTHNAME(visited_at)
ORDER BY mes;
```

### Comparación año actual vs anterior
```sql
SELECT 
    YEAR(visited_at) as anio,
    MONTH(visited_at) as mes,
    COUNT(*) as total_accesos
FROM page_views 
WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 2 YEAR)
GROUP BY YEAR(visited_at), MONTH(visited_at)
ORDER BY anio, mes;
```

---

## Dispositivos y Navegadores

### Top navegadores
```sql
SELECT 
    CASE 
        WHEN user_agent LIKE '%Chrome%' THEN 'Chrome'
        WHEN user_agent LIKE '%Firefox%' THEN 'Firefox'
        WHEN user_agent LIKE '%Safari%' THEN 'Safari'
        WHEN user_agent LIKE '%Edge%' THEN 'Edge'
        ELSE 'Otro'
    END as navegador,
    COUNT(*) as accesos
FROM page_views 
WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY navegador
ORDER BY accesos DESC;
```

### Dispositivos móviles vs desktop
```sql
SELECT 
    CASE 
        WHEN user_agent LIKE '%Mobile%' OR user_agent LIKE '%Android%' OR user_agent LIKE '%iPhone%' THEN 'Movil'
        ELSE 'Desktop'
    END as dispositivo,
    COUNT(*) as accesos
FROM page_views 
WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY dispositivo;
```

---

## Análisis de Sesiones

### Duración promedio de sesiones (páginas por sesión)
```sql
SELECT 
    AVG(paginas_por_sesion) as promedio_paginas
FROM (
    SELECT 
        session_id,
        COUNT(*) as paginas_por_sesion
    FROM page_views 
    WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY session_id
) as sesiones;
```

### Sesiones más largas
```sql
SELECT 
    session_id,
    COUNT(*) as paginas_visitadas,
    MIN(visited_at) as inicio_sesion,
    MAX(visited_at) as fin_sesion,
    TIMESTAMPDIFF(MINUTE, MIN(visited_at), MAX(visited_at)) as duracion_minutos
FROM page_views 
GROUP BY session_id
HAVING paginas_visitadas > 1
ORDER BY duracion_minutos DESC
LIMIT 10;
```

---

## Estadísticas de Incidencias

### Tiempo promedio de resolución (horas)
```sql
SELECT 
    AVG(TIMESTAMPDIFF(HOUR, created_at, resuelta_en)) as horas_promedio,
    MIN(TIMESTAMPDIFF(HOUR, created_at, resuelta_en)) as horas_minimo,
    MAX(TIMESTAMPDIFF(HOUR, created_at, resuelta_en)) as horas_maximo
FROM incidents 
WHERE estado = 'resuelta' 
  AND resuelta_en IS NOT NULL
  AND MONTH(created_at) = MONTH(NOW())
  AND YEAR(created_at) = YEAR(NOW());
```

### Incidencias por estado (mes actual)
```sql
SELECT 
    estado,
    COUNT(*) as cantidad
FROM incidents 
WHERE MONTH(created_at) = MONTH(NOW())
  AND YEAR(created_at) = YEAR(NOW())
GROUP BY estado 
ORDER BY cantidad DESC;
```

---

## Utilización del Calendario

### Días con clases programadas (trimestre actual)
```sql
SELECT 
    COUNT(DISTINCT DATE(e.start)) as dias_con_clases
FROM events e
INNER JOIN clases c ON e.clase_id = c.id
WHERE c.fecha BETWEEN 
    (SELECT fecha_inicio FROM periods WHERE fecha_inicio <= NOW() AND fecha_fin >= NOW() LIMIT 1)
    AND
    (SELECT fecha_fin FROM periods WHERE fecha_inicio <= NOW() AND fecha_fin >= NOW() LIMIT 1);
```

### Eventos por día de la semana
```sql
SELECT 
    DAYNAME(e.start) as dia_semana,
    COUNT(*) as cantidad_eventos
FROM events e
INNER JOIN clases c ON e.clase_id = c.id
WHERE YEAR(c.fecha) = YEAR(NOW())
GROUP BY DAYNAME(e.start), DAYOFWEEK(e.start)
ORDER BY DAYOFWEEK(e.start);
```

---

## Limpieza y Mantenimiento

### Eliminar accesos de hace más de 1 año
```sql
DELETE FROM page_views 
WHERE visited_at < DATE_SUB(NOW(), INTERVAL 1 YEAR);
```

### Ver tamaño de la tabla
```sql
SELECT 
    table_name AS 'Tabla',
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Tamaño (MB)'
FROM information_schema.TABLES 
WHERE table_schema = DATABASE()
  AND table_name = 'page_views';
```

### Contar registros por mes
```sql
SELECT 
    DATE_FORMAT(visited_at, '%Y-%m') as mes,
    COUNT(*) as registros
FROM page_views 
GROUP BY DATE_FORMAT(visited_at, '%Y-%m')
ORDER BY mes DESC;
```

---

## Consultas Avanzadas

### Tasa de conversión (visitantes anónimos que se registran)
```sql
-- Sesiones que empezaron anónimas
WITH sesiones_anonimas AS (
    SELECT DISTINCT session_id
    FROM page_views 
    WHERE user_id IS NULL
      AND visited_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
),
-- Sesiones que luego tuvieron usuario
sesiones_convertidas AS (
    SELECT DISTINCT pv.session_id
    FROM page_views pv
    INNER JOIN sesiones_anonimas sa ON pv.session_id = sa.session_id
    WHERE pv.user_id IS NOT NULL
)
SELECT 
    (SELECT COUNT(*) FROM sesiones_anonimas) as sesiones_anonimas,
    (SELECT COUNT(*) FROM sesiones_convertidas) as sesiones_convertidas,
    ROUND((SELECT COUNT(*) FROM sesiones_convertidas) * 100.0 / (SELECT COUNT(*) FROM sesiones_anonimas), 2) as tasa_conversion_porcentaje;
```

### Rutas de navegación más comunes
```sql
SELECT 
    pv1.page_type as pagina_origen,
    pv2.page_type as pagina_destino,
    COUNT(*) as frecuencia
FROM page_views pv1
INNER JOIN page_views pv2 
    ON pv1.session_id = pv2.session_id 
    AND pv2.visited_at > pv1.visited_at
    AND TIMESTAMPDIFF(MINUTE, pv1.visited_at, pv2.visited_at) <= 5
WHERE pv1.visited_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY pv1.page_type, pv2.page_type
ORDER BY frecuencia DESC
LIMIT 20;
```

### Horas pico por día de la semana
```sql
SELECT 
    DAYNAME(visited_at) as dia_semana,
    HOUR(visited_at) as hora,
    COUNT(*) as accesos
FROM page_views 
WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY DAYNAME(visited_at), DAYOFWEEK(visited_at), HOUR(visited_at)
ORDER BY DAYOFWEEK(visited_at), hora;
```

---

## Dashboard KPIs (Consulta Integrada)

```sql
-- KPIs principales del mes actual
SELECT 
    -- Accesos totales
    (SELECT COUNT(*) FROM page_views 
     WHERE MONTH(visited_at) = MONTH(NOW()) AND YEAR(visited_at) = YEAR(NOW())) 
    as accesos_totales,
    
    -- Sesiones únicas
    (SELECT COUNT(DISTINCT session_id) FROM page_views 
     WHERE MONTH(visited_at) = MONTH(NOW()) AND YEAR(visited_at) = YEAR(NOW())) 
    as sesiones_unicas,
    
    -- Usuarios registrados
    (SELECT COUNT(*) FROM page_views 
     WHERE user_id IS NOT NULL AND MONTH(visited_at) = MONTH(NOW()) AND YEAR(visited_at) = YEAR(NOW())) 
    as accesos_registrados,
    
    -- Visitantes anónimos
    (SELECT COUNT(*) FROM page_views 
     WHERE user_id IS NULL AND MONTH(visited_at) = MONTH(NOW()) AND YEAR(visited_at) = YEAR(NOW())) 
    as accesos_anonimos,
    
    -- Calendario público
    (SELECT COUNT(*) FROM page_views 
     WHERE page_type = 'calendario_publico' AND MONTH(visited_at) = MONTH(NOW()) AND YEAR(visited_at) = YEAR(NOW())) 
    as accesos_calendario_publico,
    
    -- Calendario admin
    (SELECT COUNT(*) FROM page_views 
     WHERE page_type = 'calendario_admin' AND MONTH(visited_at) = MONTH(NOW()) AND YEAR(visited_at) = YEAR(NOW())) 
    as accesos_calendario_admin,
    
    -- Tiempo promedio resolución incidencias (horas)
    (SELECT ROUND(AVG(TIMESTAMPDIFF(HOUR, created_at, resuelta_en)), 1) FROM incidents 
     WHERE estado = 'resuelta' AND resuelta_en IS NOT NULL 
     AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())) 
    as tiempo_promedio_incidencias;
```

---

## Notas Importantes

### Índices
La tabla `page_views` tiene índices en:
- `(page_type, visited_at)` - para filtros por tipo
- `(user_id, visited_at)` - para análisis por usuario  
- `(visited_at)` - para rangos de fechas

Esto hace que las consultas anteriores sean muy rápidas.

### Rendimiento
- Para grandes volúmenes de datos, considera agregar `LIMIT`
- Usa rangos de fechas en consultas históricas
- Programa limpiezas periódicas de datos antiguos

### Fechas
- `NOW()` - fecha/hora actual
- `CURDATE()` - fecha actual (sin hora)
- `DATE_SUB(NOW(), INTERVAL X DAY)` - restar días

---

## Ejecución en Laravel

Puedes ejecutar estas consultas desde Laravel:

```php
use Illuminate\Support\Facades\DB;

// Ejemplo
$resultado = DB::select("
    SELECT page_type, COUNT(*) as total 
    FROM page_views 
    WHERE visited_at >= NOW() - INTERVAL 30 DAY
    GROUP BY page_type
");
```

O usando Tinker:
```bash
php artisan tinker
>>> DB::select("SELECT COUNT(*) as total FROM page_views")
```

