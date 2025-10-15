# Migración de Cohortes a Año de Ingreso

## 📋 Resumen de Cambios

Esta migración adapta el sistema de **cohortes** (ciclo académico global) a **año de ingreso** (año en que ingresó el estudiante), permitiendo que cada cohorte de ingreso tenga sus propias fechas de períodos académicos.

### ¿Qué cambió?

1. **Base de Datos:**
   - Se agregó el campo `anio_ingreso` a la tabla `periods` (NO a users)
   - El campo `cohorte` se mantiene por compatibilidad temporal
   - Los usuarios NO tienen año de ingreso asociado

2. **Modelos:**
   - `Period`: Nuevo scope `deAnioIngreso()` para filtrar por año de ingreso
   - Los usuarios simplemente ven todos los períodos disponibles

3. **Controladores:**
   - Todos los controladores ahora filtran por `anio_ingreso` en lugar de `cohorte`
   - Todos los usuarios (sin importar su rol) pueden ver todos los años de ingreso
   - Los usuarios filtran períodos usando el selector de año de ingreso

4. **Vistas:**
   - Todos los selectores de "Ciclo Académico" ahora muestran "Año de Ingreso"
   - Las vistas muestran información filtrada por año de ingreso seleccionado

## 🚀 Instrucciones de Instalación

### Paso 1: Ejecutar Migraciones

```bash
php artisan migrate
```

Esto ejecutará las siguientes migraciones:
- `2025_01_15_000001_add_anio_ingreso_to_users_table.php`
- `2025_01_15_000002_modify_periods_table_for_anio_ingreso.php`

### Paso 2: (Opcional) Verificar el Seeder

```bash
php artisan db:seed --class=AsignarAnioIngresoSeeder
```

Este seeder ya no es necesario porque los usuarios NO tienen año de ingreso asociado. El año de ingreso solo se usa para los períodos académicos.

### Paso 3: Crear Períodos para los Años de Ingreso

```bash
# Crear períodos para Ingreso 2024 y 2025
php artisan db:seed --class=PeriodosIngresoSeeder
```

Este seeder crea:
- **Ingreso 2024:** 6 trimestres (Año 1 y Año 2)
- **Ingreso 2025:** 6 trimestres (Año 1 y Año 2)

### Paso 4: Verificar la Migración

```bash
# Verificar que los períodos tienen año de ingreso
php artisan tinker
>>> Period::whereNotNull('anio_ingreso')->count()
>>> Period::select('anio_ingreso', DB::raw('count(*) as total'))->groupBy('anio_ingreso')->get()
```

## 📝 Cómo Funciona el Nuevo Sistema

### Para Todos los Usuarios:
- Los usuarios NO tienen un año de ingreso asociado
- Todos los usuarios pueden ver todos los años de ingreso disponibles
- Pueden filtrar por año de ingreso usando el selector en cada sección
- Por defecto se muestra el año de ingreso más reciente

### El Año de Ingreso:
- Se usa SOLO para los períodos académicos
- Permite que diferentes años de ingreso tengan diferentes fechas de períodos
- Ejemplo: Ingreso 2024 tiene períodos con fechas específicas, Ingreso 2025 tiene otras fechas

### Ejemplo de Uso:

**Períodos para Ingreso 2024:**
- Trimestre I: 01/03/2024 - 31/05/2024
- Trimestre II: 01/06/2024 - 31/08/2024
- etc.

**Períodos para Ingreso 2025:**
- Trimestre I: 01/03/2025 - 31/05/2025
- Trimestre II: 01/06/2025 - 31/08/2025
- etc.

Los usuarios pueden cambiar entre diferentes años de ingreso usando el selector y ver los períodos correspondientes.

## 🔧 Configuración Adicional

### Crear Períodos para un Año de Ingreso Específico

```bash
php artisan tinker
>>> Period::create([
    'numero' => 1,
    'anio' => 2024,
    'anio_ingreso' => 2024,
    'fecha_inicio' => '2024-03-01',
    'fecha_fin' => '2024-05-31',
    'cohorte' => '2024-2025'
]);
```

## 📊 Estructura de Datos

### Tabla `users`
```sql
- id
- name
- email
- rol
- ...
(NOTA: Los usuarios NO tienen año de ingreso)
```

### Tabla `periods`
```sql
- id
- numero
- anio
- cohorte (mantenido por compatibilidad)
- anio_ingreso (NUEVO) - Año de ingreso al que pertenece este período
- fecha_inicio
- fecha_fin
```

## ⚠️ Notas Importantes

1. **Compatibilidad:** El campo `cohorte` se mantiene en la base de datos por compatibilidad, pero ya no se usa en la lógica de la aplicación.

2. **Usuarios:** Los usuarios NO tienen un año de ingreso asociado. Todos pueden ver todos los períodos y filtrar por año de ingreso.

3. **Períodos Existentes:** Los períodos existentes mantienen su `cohorte` y se les asigna automáticamente un `anio_ingreso` basado en su año de inicio.

4. **Nuevos Períodos:** Al crear nuevos períodos, asegúrate de asignarles el `anio_ingreso` correcto.

## 🐛 Solución de Problemas

### No se ven períodos
- Verifica que existen períodos con `anio_ingreso` asignado
- Verifica que el selector de año de ingreso tiene opciones

### Los períodos no se muestran correctamente
- Verifica que los períodos tienen `anio_ingreso` asignado
- Ejecuta la migración de nuevo si es necesario

### El selector de año de ingreso no funciona
- Verifica que hay períodos con diferentes `anio_ingreso`
- Verifica que la vista está recibiendo los datos correctos

## 📞 Soporte

Si tienes problemas con la migración, verifica:
1. Que las migraciones se ejecutaron correctamente
2. Que el seeder se ejecutó correctamente
3. Que los usuarios tienen `anio_ingreso` asignado
4. Que los períodos tienen `anio_ingreso` asignado

## ✅ Checklist de Verificación

- [ ] Migraciones ejecutadas correctamente
- [ ] Períodos tienen `anio_ingreso` asignado
- [ ] Todos los usuarios pueden ver todos los años de ingreso
- [ ] El selector de año de ingreso funciona correctamente
- [ ] Las vistas muestran correctamente el año de ingreso seleccionado
- [ ] Los filtros funcionan correctamente
- [ ] Los períodos se filtran correctamente por año de ingreso

