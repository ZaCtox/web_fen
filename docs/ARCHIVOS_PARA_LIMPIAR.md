# 🗂️ Análisis de Archivos para Limpieza - Web FEN

## ✅ MODELOS Y CONTROLADORES - ESTADO

### Modelos en Uso ✅
1. **DailyReport** ✅ - Reportes diarios (reemplazó a Bitacora)
2. **ReportEntry** ✅ - Entradas de reportes
3. **Staff** ✅ - Personal
4. **Magister** ✅ - Programas de magíster
5. **Course** ✅ - Cursos
6. **Clase** ✅ - Clases
7. **ClaseSesion** ✅ - Sesiones de clase
8. **Room** ✅ - Salas
9. **Period** ✅ - Períodos académicos
10. **Incident** ✅ - Incidencias
11. **IncidentLog** ✅ - Logs de incidencias (se usa internamente)
12. **Emergency** ✅ - Emergencias
13. **Event** ✅ - Eventos del calendario
14. **User** ✅ - Usuarios
15. **Informe** ✅ - Informes/Archivos FEN (se usa en sección pública)
16. **Novedad** ✅ - Novedades (noticias públicas)
17. **Notification** ✅ - Notificaciones (navbar + helpers.php)

### Modelos NO Utilizados ❌
1. **Bitacora** ❌ - Tiene controlador (BitacoraController) PERO NO está en rutas
   - **Recomendación**: ELIMINAR - fue reemplazado por DailyReport
   - **Ubicación**: `app/Models/Bitacora.php` + `app/Http/Controllers/BitacoraController.php`

## 🗑️ ARCHIVOS A ELIMINAR

### 1. Modelos No Utilizados
```bash
app/Models/Bitacora.php          # Reemplazado por DailyReport
```

**NOTA:** Notification SÍ se usa (en navbar y helpers.php)

### 2. Controladores No Utilizados
```bash
app/Http/Controllers/BitacoraController.php   # No está en rutas
```

### 3. Archivos Temporales de Pruebas
```bash
check_api.php
check_sesiones.php
tester.php
```

### 4. Archivos Cache Temporales
```bash
bootstrap/cache/*.tmp   # Todos los archivos .tmp
```

## 📦 ARCHIVOS A MOVER

### Documentación API → `docs/api/`
```bash
move API_CONTROLLERS_ANALYSIS.md docs/api/
move API_ROUTES_COMPLETE.md docs/api/
move API_IMPROVEMENTS_SUMMARY.md docs/api/
move PROMPT_ANDROID_KOTLIN_APP.md docs/api/
```

### Documentación Features → `docs/features/`
```bash
move IMPLEMENTACION_HCI_COMPLETA.md docs/features/
move GESTION_NOVEDADES_COMPLETO.md docs/features/
move IMPLEMENTACION_NOVEDADES_PUBLICAS.md docs/features/
move IMPLEMENTACION_TIPO_REGISTROS.md docs/features/
move MEJORAS_ESTADISTICAS_INCIDENCIAS.md docs/features/
move MEJORAS_HCI_PERFIL.md docs/features/
```

### Documentación Legacy → `docs/legacy/`
```bash
move ACTUALIZACION_BOTONES_DOWNLOAD_HCI.md docs/legacy/
move ACTUALIZACION_SEEDERS_Y_CONTROLADORES.md docs/legacy/
move ANALISIS_HCI_STAFF_COMPLETO.md docs/legacy/
move ANALISIS_HCI_STAFF.md docs/legacy/
move ANALISIS_Y_CORRECCION_PERIODS.md docs/legacy/
move BOTONES_DOWNLOAD_AGREGADOS.md docs/legacy/
move BOTONES_TAMAÑO_CONSISTENTE_FINAL.md docs/legacy/
move COMPONENTE_ACTION_BUTTON.md docs/legacy/
move CORRECCION_ERROR_INCIDENCIAS_FILTRADAS.md docs/legacy/
move ELIMINACION_MALLAS_CURRICULARES.md docs/legacy/
move ESTANDARIZACION_ICONOS_BOTONES.md docs/legacy/
move MIGRACION_ANIO_INGRESO.md docs/legacy/
move RESUMEN_BOTONES_HCI.md docs/legacy/
move RESUMEN_FINAL_ELIMINACION_MALLAS.md docs/legacy/
move RESUMEN_SESION.md docs/legacy/
```

## ⚠️ MIGRACIONES RELACIONADAS A MODELOS ELIMINADOS

Si eliminamos Bitacora y Notification, también deberíamos:

1. **Buscar migraciones relacionadas:**
   - `database/migrations/*_create_bitacoras_table.php`
   - `database/migrations/*_create_notifications_table.php`

2. **Opciones:**
   - Dejarlas (no afectan si la tabla no se usa)
   - Moverlas a una carpeta `database/migrations/deprecated/`
   - Eliminarlas Y hacer rollback de las tablas

## 📊 RESUMEN

### A Eliminar:
- 1 Modelo (Bitacora)
- 1 Controlador (BitacoraController)
- 3 Archivos de pruebas (check_api, check_sesiones, tester)
- ~20 archivos .tmp en bootstrap/cache

### A Mover:
- 4 archivos a `docs/api/`
- 6 archivos a `docs/features/`
- 15 archivos a `docs/legacy/`

### Total:
- **~26 archivos para limpiar** (sin contar Notification que SÍ se usa)
- **25 archivos de documentación para organizar**

## 🎯 IMPACTO

- ✅ Raíz del proyecto limpia (solo archivos esenciales)
- ✅ Documentación organizada por categorías
- ✅ Código más mantenible
- ✅ Sin archivos huérfanos
- ✅ Mejor para nuevos desarrolladores

## ⏭️ PRÓXIMO PASO

¿Quieres que ejecute la limpieza automáticamente?

