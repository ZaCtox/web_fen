# 🎨 Análisis de Vistas (Views) - Web FEN

## 📊 RESUMEN EJECUTIVO

### ✅ Vistas en Uso (Carpetas Activas)
- ✅ **auth/** - Sistema de autenticación (login, registro, passwords)
- ✅ **daily-reports/** - Reportes diarios (reemplazó a bitacoras)
- ✅ **calendario/** - Calendario de eventos
- ✅ **clases/** - Gestión de clases
- ✅ **courses/** - Gestión de cursos
- ✅ **emergencies/** - Gestión de emergencias
- ✅ **incidencias/** - Gestión de incidencias
- ✅ **informes/** - Archivos FEN (sección pública)
- ✅ **magisters/** - Programas de magíster
- ✅ **novedades/** - Noticias/novedades públicas
- ✅ **periods/** - Períodos académicos
- ✅ **profile/** - Perfil de usuario
- ✅ **public/** - Vistas públicas (sitio web público)
- ✅ **rooms/** - Gestión de salas
- ✅ **staff/** - Gestión de personal
- ✅ **usuarios/** - Gestión de usuarios
- ✅ **components/** - Componentes reutilizables
- ✅ **layouts/** - Layouts principales
- ✅ **emails/** - Plantillas de emails

### ❌ Vistas NO Usadas (Para Eliminar/Mover)

#### 1. **bitacoras/** ❌ - OBSOLETO
```
resources/views/bitacoras/
├── create.blade.php
├── edit.blade.php
├── index.blade.php
├── pdf.blade.php
└── show.blade.php
```
**Razón:** Reemplazado completamente por `daily-reports/`
**Controlador:** BitacoraController existe pero NO está en rutas
**Acción:** ELIMINAR (ya no se usa)

#### 2. **mallas-curriculares/** ❌ - VACÍO
```
resources/views/mallas-curriculares/
(carpeta vacía)
```
**Razón:** Carpeta vacía, funcionalidad eliminada
**Acción:** ELIMINAR carpeta vacía

#### 3. **examples/** ⚠️ - DEMOS DE DESARROLLO
```
resources/views/examples/
├── hci-demo.blade.php
├── microinteractions-demo.blade.php
```
**Razón:** Vistas de demostración/testing de componentes HCI
**Estado:** Están en rutas (líneas 28 y 33 de web.php)
**Opciones:**
  - Opción A: Eliminar si ya no se necesitan demos
  - Opción B: Mover a `resources/views/dev/` 
  - Opción C: Dejar solo en desarrollo (comentar rutas en producción)

**Recomendación:** Mover a `resources/views/dev/examples/` y comentar rutas

## 📋 ANÁLISIS DETALLADO POR CARPETA

### ✅ CARPETAS ACTIVAS (Mantener)

#### 1. **daily-reports/** (6 archivos)
```
✅ create.blade.php   - Crear reporte
✅ edit.blade.php     - Editar reporte
✅ form.blade.php     - Formulario wizard
✅ index.blade.php    - Lista de reportes
✅ pdf.blade.php      - PDF del reporte
✅ show.blade.php     - Ver detalle
```
**Estado:** En uso activo ✅
**Controlador:** DailyReportController
**Ruta:** `/daily-reports`

#### 2. **incidencias/** (6 archivos)
```
✅ create.blade.php
✅ edit.blade.php
✅ form.blade.php
✅ index.blade.php
✅ pdf.blade.php
✅ show.blade.php
```
**Estado:** En uso activo ✅
**Controlador:** IncidentController
**Ruta:** `/incidencias`

#### 3. **clases/** (7 archivos)
```
✅ create.blade.php
✅ edit.blade.php
✅ export.blade.php
✅ form-wizard.blade.php
✅ index.blade.php
✅ show.blade.php
└── partials/
    ✅ sesion-modal.blade.php
```
**Estado:** En uso activo ✅
**Controlador:** ClaseController
**Ruta:** `/clases`

#### 4. **calendario/** (3 archivos)
```
✅ index.blade.php
✅ modal-crear.blade.php
✅ modal-ver.blade.php
```
**Estado:** En uso activo ✅
**Controlador:** CalendarioController
**Ruta:** `/calendario`

#### 5. **courses/** (5 archivos)
```
✅ create.blade.php
✅ edit.blade.php
✅ form-wizard.blade.php
✅ form.blade.php
✅ index.blade.php
```
**Estado:** En uso activo ✅
**Controlador:** CourseController
**Ruta:** `/courses`

**NOTA:** Tiene `form-wizard.blade.php` Y `form.blade.php`
- Verificar si ambos se usan o si uno es obsoleto

#### 6. **magisters/** (5 archivos)
```
✅ create.blade.php
✅ edit.blade.php
✅ form.blade.php
✅ index.blade.php
✅ show.blade.php
```
**Estado:** En uso activo ✅

#### 7. **rooms/** (7 archivos)
```
✅ create.blade.php
✅ edit.blade.php
✅ form.blade.php
✅ import.blade.php
✅ index.blade.php
✅ show.blade.php
✅ vista-publica.blade.php
```
**Estado:** En uso activo ✅

#### 8. **staff/** (5 archivos)
```
✅ create.blade.php
✅ edit.blade.php
✅ form.blade.php
✅ index.blade.php
✅ show.blade.php
```
**Estado:** En uso activo ✅

#### 9. **informes/** (4 archivos)
```
✅ create.blade.php
✅ edit.blade.php
✅ form.blade.php
✅ index.blade.php
```
**Estado:** En uso activo ✅
**Uso:** Archivos FEN (sección pública)

#### 10. **novedades/** (5 archivos)
```
✅ create.blade.php
✅ edit.blade.php
✅ form.blade.php
✅ index.blade.php
✅ show.blade.php
```
**Estado:** En uso activo ✅
**Uso:** Noticias públicas

#### 11. **periods/** (5 archivos)
```
✅ create.blade.php
✅ edit.blade.php
✅ form.blade.php
✅ index.blade.php
✅ show.blade.php
```
**Estado:** En uso activo ✅

#### 12. **emergencies/** (5 archivos)
```
✅ create.blade.php
✅ edit.blade.php
✅ form.blade.php
✅ index.blade.php
✅ show.blade.php
```
**Estado:** En uso activo ✅

#### 13. **usuarios/** (3 archivos)
```
✅ create.blade.php
✅ edit.blade.php
✅ index.blade.php
```
**Estado:** En uso activo ✅

#### 14. **public/** (8 archivos)
```
✅ archivos.blade.php
✅ calendario.blade.php
✅ eventos.blade.php
✅ home.blade.php
✅ layout.blade.php
✅ novedades-show.blade.php
✅ novedades.blade.php
✅ salas.blade.php
```
**Estado:** En uso activo ✅
**Uso:** Sitio web público

### 🧩 **components/** (68 archivos)
**Estado:** La mayoría en uso ✅

#### Componentes HCI (Sistema de Diseño)
```
✅ hci-button.blade.php
✅ hci-field.blade.php
✅ hci-select.blade.php
✅ hci-textarea.blade.php
✅ hci-checkbox.blade.php
✅ hci-form-group.blade.php
✅ hci-form-section.blade.php
✅ hci-wizard-layout.blade.php
✅ hci-breadcrumb.blade.php
✅ hci-nav-link.blade.php
✅ hci-nav-group.blade.php
✅ hci-loading.blade.php
✅ hci-feedback.blade.php
✅ hci-confirm.blade.php
✅ hci-validation.blade.php
✅ hci-notification-system.blade.php
✅ hci-progress-sidebar.blade.php
```

#### Componentes Progress Sidebar (Navegación contextual)
```
✅ clases-progress-sidebar.blade.php
✅ classes-progress-sidebar.blade.php    # ⚠️ Duplicado?
✅ courses-progress-sidebar.blade.php
✅ daily-reports-progress-sidebar.blade.php
✅ emergency-progress-sidebar.blade.php
✅ incidencias-progress-sidebar.blade.php
✅ informes-progress-sidebar.blade.php
✅ magisters-progress-sidebar.blade.php
✅ novedades-progress-sidebar.blade.php
✅ periods-progress-sidebar.blade.php
✅ rooms-progress-sidebar.blade.php
✅ staff-progress-sidebar.blade.php
✅ usuarios-progress-sidebar.blade.php
```

**⚠️ NOTA:** `clases-progress-sidebar.blade.php` y `classes-progress-sidebar.blade.php`
- Probablemente duplicados (español vs inglés)
- Verificar cuál se usa y eliminar el otro

#### Componentes de Demo/Desarrollo
```
⚠️ hci-microinteractions-demo.blade.php  # Solo para demos
```

#### Componentes Estándar (Mantener)
```
✅ action-button.blade.php
✅ button-fen.blade.php
✅ primary-button.blade.php
✅ secondary-button.blade.php
✅ danger-button.blade.php
✅ text-input.blade.php
✅ input-label.blade.php
✅ input-error.blade.php
✅ modal.blade.php
✅ dropdown.blade.php
✅ dropdown-link.blade.php
✅ nav-link.blade.php
✅ responsive-nav-link.blade.php
✅ loading-spinner.blade.php
✅ loading-overlay.blade.php
✅ skeleton-table.blade.php
✅ empty-state.blade.php
✅ dashboard-card.blade.php
✅ estado-icon.blade.php
✅ magister-color.blade.php
✅ leyenda-magister.blade.php
✅ filtros-calendario.blade.php
✅ scroll-to-top.blade.php
✅ footer.blade.php
✅ logo-fen.blade.php
✅ application-logo.blade.php
✅ agregar.blade.php
✅ back.blade.php
✅ save.blade.php
```

## 🗑️ PLAN DE LIMPIEZA DE VISTAS

### Acción 1: ELIMINAR carpetas obsoletas
```bash
# Eliminar bitacoras/ (reemplazado por daily-reports/)
rm -rf resources/views/bitacoras/

# Eliminar mallas-curriculares/ (carpeta vacía)
rm -rf resources/views/mallas-curriculares/
```

### Acción 2: MOVER vistas de desarrollo
```bash
# Crear carpeta dev
mkdir resources/views/dev/

# Mover examples/
mv resources/views/examples/ resources/views/dev/

# Actualizar rutas en web.php (comentar o cambiar a 'dev.examples.hci-demo')
```

### Acción 3: REVISAR duplicados
```
⚠️ Verificar:
1. courses/form-wizard.blade.php vs courses/form.blade.php
2. clases-progress-sidebar.blade.php vs classes-progress-sidebar.blade.php
3. hci-microinteractions-demo.blade.php (¿se usa?)
```

## 📊 RESUMEN FINAL

### A Eliminar:
- ❌ `resources/views/bitacoras/` (5 archivos)
- ❌ `resources/views/mallas-curriculares/` (carpeta vacía)

### A Mover:
- 📦 `resources/views/examples/` → `resources/views/dev/examples/` (2 archivos)

### A Revisar/Verificar:
- ⚠️ Duplicados en components/ (2-3 archivos)
- ⚠️ forms dobles en courses/

### Total de limpieza:
- **7 archivos a eliminar**
- **2 archivos a mover**
- **3-4 archivos a revisar**

## ✅ BENEFICIOS

- ✅ Elimina vistas obsoletas (bitacoras)
- ✅ Carpetas vacías removidas
- ✅ Vistas de desarrollo organizadas
- ✅ Estructura más limpia y clara
- ✅ Más fácil de navegar para nuevos desarrolladores

