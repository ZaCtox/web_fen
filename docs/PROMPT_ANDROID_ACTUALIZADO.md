# ✅ Actualización del Prompt para Android - Web FEN

## 📅 Fecha: 15 de Octubre 2025

## 🎯 CAMBIOS REALIZADOS AL PROMPT

### 1. **Enfoque por Fases** ⭐

#### Estructura Anterior:
- Pedía implementar TODO de una vez
- Dashboard, reportes, incidencias, calendario, etc.
- Podía ser abrumador para Cursor
- Builds muy largos

#### Estructura Nueva (Mejorada):
```
📱 FASE 1: FUNDAMENTOS (Implementar primero)
├── Login/Logout con token
├── Vistas Públicas:
│   ├── Calendario de eventos
│   ├── Lista de salas
│   ├── Personal (staff)
│   └── Programas de magíster
└── Bottom Navigation básica (3 tabs)

📱 FASE 2: (Después)
└── Dashboard y Reportes Diarios

📱 FASE 3: (Después)
└── Incidencias y Admin
```

**Beneficios:**
- ✅ Desarrollo incremental
- ✅ Testing más temprano
- ✅ Menos abrumador
- ✅ Feedback progresivo

---

### 2. **Priorización Clara de Endpoints** 🔑

#### Endpoints para Fase 1 (SOLO ESTOS):
```
Públicos (SIN AUTH):
✅ GET /api/public/events
✅ GET /api/public/rooms
✅ GET /api/public/staff
✅ GET /api/public/magisters
✅ GET /api/public/magisters-with-course-count
✅ GET /api/public/courses
✅ GET /api/emergencies/active

Autenticación:
✅ POST /api/login
✅ GET /api/user (con token)
✅ POST /api/logout (con token)
```

#### Para Fases Posteriores:
```
❌ Daily Reports (Fase 2)
❌ Incidents (Fase 2)
❌ Admin Dashboard (Fase 3)
❌ CRUD operations (Fase 3)
```

---

### 3. **Archivo Completo de Rutas Incluido** 📄

Se agregó el contenido completo de `routes/api.php` para que Cursor entienda:
- La estructura completa de la API
- Qué rutas están disponibles
- Cuáles están protegidas y cuáles no
- Los nombres de las rutas

**Ubicación en el prompt:** Final del documento

---

### 4. **Ejemplos de Respuestas Detallados** 📋

Se agregaron 7 ejemplos de respuestas JSON:
1. GET /api/public/magisters
2. GET /api/public/staff
3. GET /api/public/rooms
4. GET /api/public/events
5. GET /api/emergencies/active
6. POST /api/login (success y error)
7. GET /api/user (con token)

**Beneficio:** Cursor entenderá exactamente la estructura de datos esperada

---

### 5. **Pantallas Detalladas para Fase 1** 📱

#### Antes:
- Lista simple de pantallas
- Sin detalles de qué mostrar

#### Después:
Cada pantalla con descripción completa:

**LoginScreen:**
- Input de email
- Input de password (con show/hide)
- Botón "Iniciar Sesión"
- Mensaje de error si falla
- Loading state

**PublicHomeScreen:**
- Banner de emergencia activa
- Grid con accesos rápidos
- Lista de novedades recientes

**PublicCalendarScreen:**
- Vista de calendario mensual
- Filtros (Programa, Sala)
- Lista de eventos por día
- Colores por programa

**PublicRoomsScreen:**
- Cards de salas
- Nombre, ubicación, capacidad
- Iconos de equipamiento
- Búsqueda por nombre

**PublicStaffScreen:**
- Cards con foto, nombre, cargo
- Email y teléfono
- Filtro por cargo
- Búsqueda por nombre

**PublicMagistersScreen:**
- Lista de programas con colores
- Contador de cursos
- Ver cursos al hacer clic

**ProfileScreen:**
- Si NO logueado: Botón "Iniciar Sesión"
- Si SÍ logueado: Datos + botón "Cerrar Sesión"

---

### 6. **Instrucciones Claras y Enfocadas** 📝

#### Agregado al prompt:
```
Por favor, implementa **SOLO LA FASE 1**:

1. Estructura del proyecto (MVVM + Hilt + Compose)
2. Pantallas de Fase 1 solamente
3. Componentes clave (AuthRepository, PublicRepository)
4. NO implementes dashboard, reportes, incidencias todavía

Una vez que la Fase 1 esté funcionando, continuaremos.
```

**Beneficio:** 
- Cursor se enfoca en lo esencial
- No se distrae con funcionalidades complejas
- Resultados más rápidos

---

## 📊 COMPARACIÓN: ANTES vs DESPUÉS

| Aspecto | Antes ❌ | Después ✅ |
|---------|----------|------------|
| **Alcance** | Todo de una vez | Fase 1 solo |
| **Endpoints** | Todos (~40 rutas) | Solo públicos + auth (~10 rutas) |
| **Pantallas** | ~15 pantallas | ~7 pantallas |
| **Complejidad** | Alta | Baja |
| **Tiempo estimado** | Horas/días | Minutos/horas |
| **Testing** | Al final | Inmediato |
| **Ejemplos API** | Genérico | Específicos de cada endpoint |
| **Rutas API** | Solo descripción | Código completo incluido |

---

## 🎯 RESULTADO ESPERADO DE LA FASE 1

### App Android que permite:

✅ **Sin login:**
- Ver calendario de eventos públicos
- Ver salas disponibles
- Ver personal de la facultad
- Ver programas de magíster
- Ver emergencias activas

✅ **Con login:**
- Todo lo anterior +
- Ver perfil del usuario
- Persistencia de sesión (no pide login cada vez)
- Logout cuando quiera

✅ **Navegación:**
- Bottom Navigation con 3 tabs:
  * 🏠 Inicio (vistas públicas)
  * 📅 Calendario (eventos)
  * 👤 Perfil (login/datos usuario)

---

## 📁 UBICACIÓN DEL PROMPT

**Archivo:** `docs/api/PROMPT_ANDROID_KOTLIN_APP.md`

**Cómo usar:**
1. Crea proyecto Android en Android Studio
2. Abre el proyecto en Cursor
3. Copia TODO el contenido del prompt
4. Pégalo en el chat de Cursor
5. Cursor creará la Fase 1

**Después de Fase 1:**
- Pruebas la app
- Si funciona bien, pides Fase 2
- Iterativo y controlado

---

## ✅ VENTAJAS DEL NUEVO PROMPT

### 1. **Más Enfocado** 🎯
- Scope claro y limitado
- No se distrae con funcionalidades avanzadas

### 2. **Más Detallado** 📋
- Ejemplos de respuestas JSON
- Código completo de api.php
- Descripción de cada pantalla

### 3. **Desarrollo Progresivo** 📈
- Fase 1 → pruebas → Fase 2 → pruebas → Fase 3
- Menos riesgo de bugs grandes
- Más fácil de debugear

### 4. **Mejor para Cursor** 🤖
- Instrucciones claras y acotadas
- Ejemplos concretos
- Menos ambigüedad

### 5. **Mejor para Ti** 👨‍💻
- Ves resultados rápido
- Puedes probar antes
- Aprendes progresivamente

---

## 📝 CONTENIDO DEL PROMPT ACTUALIZADO

### Secciones incluidas:

1. ✅ **Instrucción Principal** (Fase 1 primero)
2. ✅ **Información de la API** (Base URL, auth)
3. ✅ **Endpoints detallados** (solo Fase 1)
4. ✅ **Arquitectura** (MVVM + Hilt + Compose)
5. ✅ **Paleta de colores** (exacta de la web)
6. ✅ **Tipografía** (Material Design 3)
7. ✅ **Componentes de UI** (ejemplos en Kotlin)
8. ✅ **Estructura de pantallas** (detallada)
9. ✅ **Iconos y recursos** (Material Icons)
10. ✅ **Dependencias** (Retrofit, Hilt, Room, etc.)
11. ✅ **Ejemplos de respuestas** (JSON reales)
12. ✅ **Archivo api.php completo** (referencia)

---

## 🚀 PRÓXIMOS PASOS

### Para implementar:
1. Crear proyecto Android en Android Studio
2. Abrir en Cursor
3. Pegar el prompt completo
4. Cursor implementa Fase 1
5. Probar login y vistas públicas
6. Si todo funciona → Pedir Fase 2

### Para solicitar Fase 2 (después):
```
Ya tengo la Fase 1 funcionando (login + vistas públicas). 
Ahora implementa la Fase 2:
- Dashboard con estadísticas
- Lista de reportes diarios
- Ver detalle de reportes
- Crear nuevo reporte con indicador de severidad
```

---

**Estado:** ✅ PROMPT ACTUALIZADO Y OPTIMIZADO
**Tamaño:** ~1400 líneas
**Detalles:** Completo con ejemplos y código
**Fases:** 3 fases bien definidas
**Listo para usar:** SÍ 🚀
