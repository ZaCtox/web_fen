# Arquitectura del Sistema y Diseño de Base de Datos

## 3.2 Arquitectura del Sistema

### 3.2.1 Patrón Modelo-Vista-Controlador (MVC)

La plataforma web se desarrolló siguiendo el patrón arquitectónico **Modelo-Vista-Controlador (MVC)**, implementado de manera nativa por el framework Laravel. Este patrón permite la separación de responsabilidades en tres componentes fundamentales, mejorando la mantenibilidad, escalabilidad y testabilidad del código.

#### **Componente Modelo (Model)**

El Modelo representa la **capa de datos y lógica de negocio** del sistema. En Laravel, los modelos son clases PHP que interactúan directamente con la base de datos mediante el ORM Eloquent.

**Responsabilidades principales:**
- Definir la estructura de los datos (tablas, columnas, tipos)
- Establecer relaciones entre entidades (uno a muchos, muchos a muchos)
- Implementar reglas de negocio y validaciones
- Proporcionar métodos de consulta personalizados (scopes)
- Gestionar eventos del ciclo de vida de los datos

**Ejemplo de modelos implementados:**
```
- User: Gestión de usuarios del sistema
- Magister: Programas académicos de postgrado
- Course: Asignaturas de cada programa
- Clase: Instancias de cursos en períodos específicos
- ClaseSesion: Sesiones individuales de clases
- Period: Trimestres académicos con fechas
- Room: Salas físicas y sus características
- Incident: Sistema de tickets de incidencias
- Informe: Documentos oficiales
- Novedad: Comunicaciones y anuncios
```

**Relaciones entre modelos:**
- Un `Magister` tiene muchos `Course` (programa → cursos)
- Un `Course` pertenece a un `Magister` y tiene muchas `Clases`
- Una `Clase` pertenece a un `Period` y tiene muchas `ClaseSesion`
- Un `User` puede crear muchos `Incident`, `Informe`, `Novedad`
- Una `Room` puede tener muchas `Clases` asignadas

#### **Componente Vista (View)**

Las Vistas constituyen la **capa de presentación** que el usuario final visualiza e interactúa. Laravel utiliza el motor de plantillas **Blade** que combina HTML con directivas PHP de manera elegante.

**Responsabilidades principales:**
- Renderizar la interfaz de usuario
- Mostrar datos recibidos desde los controladores
- Capturar entradas del usuario (formularios)
- Presentar información de manera estructurada y estética

**Estructura de vistas implementadas:**
```
resources/views/
├── layouts/           # Plantillas base (app.blade.php, guest.blade.php)
├── components/        # Componentes reutilizables (botones, alertas, modales)
├── auth/             # Vistas de autenticación (login, register)
├── dashboard/        # Panel principal
├── magisters/        # Gestión de programas
├── courses/          # Gestión de cursos
├── clases/           # Gestión de clases
├── calendario/       # Calendario interactivo
├── rooms/            # Gestión de salas
├── staff/            # Directorio de personal
├── incidents/        # Sistema de incidencias
├── informes/         # Repositorio de documentos
├── novedades/        # Centro de comunicaciones
├── public/           # Vistas públicas sin autenticación
└── analytics/        # Dashboard de estadísticas
```

**Características de las vistas:**
- **Diseño responsive** con Tailwind CSS (móvil, tablet, escritorio)
- **Modo oscuro** completo en toda la plataforma
- **Componentes interactivos** con Alpine.js (dropdowns, modales, tabs)
- **Validación en tiempo real** con JavaScript
- **Feedback visual** mediante SweetAlert2

#### **Componente Controlador (Controller)**

Los Controladores actúan como **intermediarios** entre Modelos y Vistas, orquestando la lógica de la aplicación y el flujo de datos.

**Responsabilidades principales:**
- Recibir peticiones HTTP del usuario
- Validar datos de entrada
- Invocar métodos del modelo para manipular datos
- Preparar datos para las vistas
- Retornar respuestas (HTML, JSON, PDF, redirecciones)

**Tipos de controladores implementados:**

1. **Controladores Web** (routes/web.php)
   - Retornan vistas HTML para navegadores
   - Manejan sesiones y autenticación web
   - Ejemplos: `MagisterController`, `CourseController`, `ClaseController`

2. **Controladores API** (routes/api.php)
   - Retornan respuestas JSON para apps móviles
   - Autenticación con tokens Sanctum
   - Ubicados en `app/Http/Controllers/Api/`
   - Ejemplos: `Api\MagisterController`, `Api\EventController`

3. **Controladores Públicos** (routes/public.php)
   - Sin requerimiento de autenticación
   - Para información de consulta general
   - Ubicados en `app/Http/Controllers/PublicSite/`
   - Ejemplos: `PublicSite\GuestEventController`

**Ejemplo de flujo MVC completo:**

```
Usuario solicita ver calendario → 
  ↓
[RUTA] GET /calendario →
  ↓
[CONTROLADOR] CalendarioController@index →
  - Recibe petición
  - Obtiene filtros (fecha, programa)
  ↓
[MODELO] ClaseSesion::with('clase.course')->get() →
  - Consulta base de datos
  - Retorna colección de sesiones
  ↓
[CONTROLADOR] Formatea datos para calendario →
  - Convierte a formato FullCalendar
  - Aplica colores por programa
  ↓
[VISTA] calendario/index.blade.php →
  - Renderiza interfaz
  - Carga FullCalendar
  - Muestra eventos en calendario
  ↓
Usuario visualiza calendario interactivo
```

#### **Ventajas del patrón MVC en el proyecto**

**Separación de responsabilidades:**
- Lógica de negocio separada de la presentación
- Facilita trabajo en equipo (diseñadores vs programadores)
- Cambios en UI no afectan lógica de datos

**Reutilización de código:**
- Mismo modelo usado por controlador web y API
- Componentes de vista reutilizables
- Validaciones centralizadas

**Facilidad de testing:**
- Tests unitarios para modelos (lógica de negocio)
- Tests de integración para controladores (endpoints)
- Tests de feature para flujos completos

**Escalabilidad:**
- Agregar nuevas funcionalidades sin afectar código existente
- Fácil incorporar nuevos módulos
- API y web comparten misma lógica de negocio

---

## 3.3 Diseño de la Base de Datos

### 3.3.1 Motor de Base de Datos

La plataforma utiliza **SQLite** como sistema gestor de base de datos relacional. Esta elección se fundamenta en:
- **Simplicidad**: Base de datos embebida sin servidor externo
- **Portabilidad**: Archivo único fácil de respaldar y transferir
- **Rendimiento**: Suficiente para el volumen de datos esperado
- **Compatibilidad**: Total integración con Laravel

### 3.3.2 Estructura de Tablas Principales

A continuación se describen las tablas fundamentales del sistema, organizadas por módulo funcional. Esta información puede convertirse en diagramas de entidad-relación (ER) o tablas descriptivas.

---

#### **MÓDULO: USUARIOS Y AUTENTICACIÓN**

**Tabla: users**
- **Descripción:** Almacena información de usuarios del sistema (administradores, asistentes, estudiantes)
- **Campos principales:**
  - `id` (PK): Identificador único
  - `name`: Nombre completo del usuario
  - `email` (UNIQUE): Correo electrónico (credencial de acceso)
  - `password`: Contraseña hasheada con bcrypt
  - `rol`: Rol del usuario (administrador, director_programa, asistente_postgrado, estudiante)
  - `anio_ingreso`: Año de ingreso del estudiante (nullable para personal)
  - `foto`: URL de foto de perfil (Cloudinary)
  - `public_id`: Identificador Cloudinary para gestión de imagen
  - `last_login_at`: Timestamp último acceso
  - `email_verified_at`: Verificación de email
  - `created_at`, `updated_at`: Timestamps automáticos

**Tabla: sessions**
- **Descripción:** Gestión de sesiones web activas
- **Campos principales:**
  - `id` (PK): Identificador de sesión
  - `user_id` (FK): Usuario asociado
  - `ip_address`: IP del cliente
  - `user_agent`: Navegador/dispositivo
  - `last_activity`: Timestamp última actividad

**Tabla: personal_access_tokens**
- **Descripción:** Tokens de autenticación para API (Laravel Sanctum)
- **Campos principales:**
  - `id` (PK): Identificador único
  - `tokenable_id`, `tokenable_type`: Polimórfico (usuario dueño del token)
  - `name`: Nombre descriptivo del token
  - `token`: Hash del token
  - `abilities`: Permisos asociados (JSON)
  - `expires_at`: Fecha de expiración

---

#### **MÓDULO: PROGRAMAS ACADÉMICOS**

**Tabla: magisters**
- **Descripción:** Programas de magíster ofrecidos por la facultad
- **Campos principales:**
  - `id` (PK): Identificador único
  - `nombre` (UNIQUE): Nombre del programa (ej: "Magíster en Gestión")
  - `descripcion`: Descripción completa del programa
  - `director`: Nombre del director del programa
  - `email_contacto`: Email de contacto oficial
  - `telefono_contacto`: Teléfono de contacto
  - `color`: Color hexadecimal para identificación visual (#HEX)
  - `asistente`: Nombre del asistente del programa
  - `orden`: Orden de visualización en listados
  - `created_at`, `updated_at`

**Tabla: periods**
- **Descripción:** Trimestres académicos con fechas específicas
- **Campos principales:**
  - `id` (PK): Identificador único
  - `magister_id` (FK): Programa al que pertenece
  - `numero`: Número de trimestre (1, 2, 3)
  - `anio`: Año académico del programa (1 o 2)
  - `anio_ingreso`: Año de ingreso de la cohorte (2024, 2025, etc.)
  - `fecha_inicio`: Fecha inicio del trimestre
  - `fecha_fin`: Fecha fin del trimestre
  - `created_at`, `updated_at`
- **Relaciones:**
  - Pertenece a: `magisters`

**Tabla: courses**
- **Descripción:** Asignaturas/cursos de cada programa
- **Campos principales:**
  - `id` (PK): Identificador único
  - `magister_id` (FK): Programa al que pertenece
  - `nombre`: Nombre de la asignatura
  - `codigo`: Código del curso (opcional)
  - `profesor`: Nombre del profesor
  - `descripcion`: Descripción del curso
  - `sct`: Créditos SCT (Sistema de Créditos Transferibles)
  - `requisitos`: Cursos prerequisitos (JSON)
  - `tipo`: Tipo de curso (obligatorio, electivo, taller)
  - `created_at`, `updated_at`
- **Relaciones:**
  - Pertenece a: `magisters`

---

#### **MÓDULO: CLASES Y SESIONES**

**Tabla: clases**
- **Descripción:** Instancias de cursos programadas en trimestres específicos
- **Campos principales:**
  - `id` (PK): Identificador único
  - `course_id` (FK): Curso que se dicta
  - `period_id` (FK): Trimestre en que se dicta
  - `room_id` (FK, nullable): Sala asignada
  - `modality`: Modalidad (presencial, online, hibrida)
  - `dia`: Día de la semana (Viernes, Sábado)
  - `hora_inicio`: Hora inicio de la clase
  - `hora_fin`: Hora fin de la clase
  - `url_zoom`: Enlace Zoom/Meet (si aplica)
  - `created_at`, `updated_at`
- **Relaciones:**
  - Pertenece a: `courses`, `periods`, `rooms`
  - Tiene muchas: `clase_sesiones`

**Tabla: clase_sesiones**
- **Descripción:** Sesiones individuales de cada clase (fechas específicas)
- **Campos principales:**
  - `id` (PK): Identificador único
  - `clase_id` (FK): Clase a la que pertenece
  - `fecha` (UNIQUE con clase_id): Fecha específica de la sesión
  - `url_grabacion`: Enlace a grabación de la clase
  - `estado`: Estado (pendiente, completada, cancelada)
  - `observaciones`: Notas adicionales
  - `coffee_break_inicio`: Hora inicio coffee break (nullable)
  - `coffee_break_fin`: Hora fin coffee break (nullable)
  - `lunch_break_inicio`: Hora inicio lunch break (nullable)
  - `lunch_break_fin`: Hora fin lunch break (nullable)
  - `created_at`, `updated_at`
- **Relaciones:**
  - Pertenece a: `clases`
- **Índice único:** (clase_id, fecha) - Una clase no puede tener dos sesiones el mismo día

---

#### **MÓDULO: INFRAESTRUCTURA**

**Tabla: rooms**
- **Descripción:** Salas físicas disponibles para clases
- **Campos principales:**
  - `id` (PK): Identificador único
  - `name`: Nombre/código de la sala (ej: "A301")
  - `location`: Ubicación física (edificio, piso)
  - `capacity`: Capacidad de personas
  - `description`: Descripción general
  - `equipamiento`: Lista de equipos disponibles (JSON)
  - `created_at`, `updated_at`

---

#### **MÓDULO: PERSONAL**

**Tabla: staff**
- **Descripción:** Personal académico y administrativo
- **Campos principales:**
  - `id` (PK): Identificador único
  - `nombre`: Nombre completo
  - `cargo`: Cargo o posición
  - `email` (UNIQUE): Email institucional
  - `telefono`: Teléfono de contacto
  - `anexo`: Anexo telefónico interno
  - `descripcion`: Biografía o descripción
  - `foto`: URL foto perfil (Cloudinary)
  - `public_id`: ID Cloudinary
  - `created_at`, `updated_at`

---

#### **MÓDULO: INCIDENCIAS**

**Tabla: incidents**
- **Descripción:** Sistema de tickets para reportar problemas
- **Campos principales:**
  - `id` (PK): Identificador único
  - `nro_ticket`: Número de ticket generado automáticamente
  - `user_id` (FK): Usuario que reporta
  - `room_id` (FK, nullable): Sala afectada
  - `magister_id` (FK, nullable): Programa relacionado
  - `titulo`: Título breve del problema
  - `descripcion`: Descripción detallada
  - `prioridad`: Nivel (baja, media, alta, critica)
  - `estado`: Estado (pendiente, en_proceso, resuelta, cerrada)
  - `imagen`: URL de imagen adjunta
  - `public_id`: ID Cloudinary
  - `resolved_by` (FK, nullable): Usuario que resolvió
  - `resuelta_en`: Timestamp de resolución
  - `comentario`: Comentario de resolución
  - `created_at`, `updated_at`
- **Relaciones:**
  - Pertenece a: `users` (reportador), `users` (resolutor), `rooms`, `magisters`
  - Tiene muchos: `incident_logs`

**Tabla: incident_logs**
- **Descripción:** Historial de cambios en incidencias
- **Campos principales:**
  - `id` (PK): Identificador único
  - `incident_id` (FK): Incidencia asociada
  - `user_id` (FK): Usuario que realizó el cambio
  - `accion`: Descripción de la acción
  - `estado_anterior`: Estado antes del cambio
  - `estado_nuevo`: Estado después del cambio
  - `created_at`

---

#### **MÓDULO: DOCUMENTOS E INFORMES**

**Tabla: informes**
- **Descripción:** Repositorio de documentos oficiales
- **Campos principales:**
  - `id` (PK): Identificador único
  - `nombre`: Nombre del documento
  - `descripcion`: Descripción del contenido
  - `archivo`: URL o ruta del archivo
  - `mime`: Tipo MIME (application/pdf, etc.)
  - `user_id` (FK): Usuario que subió
  - `magister_id` (FK, nullable): Programa específico (null = todos)
  - `tipo`: Categoría (academico, administrativo, calendario)
  - `visible_publico`: Booleano de visibilidad pública
  - `created_at`, `updated_at`
- **Relaciones:**
  - Pertenece a: `users`, `magisters`

**Tabla: daily_reports**
- **Descripción:** Reportes diarios de actividades
- **Campos principales:**
  - `id` (PK): Identificador único
  - `user_id` (FK): Usuario creador
  - `fecha`: Fecha del reporte
  - `titulo`: Título del reporte
  - `pdf_path`: Ruta del PDF generado
  - `created_at`, `updated_at`
- **Relaciones:**
  - Pertenece a: `users`
  - Tiene muchas: `report_entries`

**Tabla: report_entries**
- **Descripción:** Entradas individuales dentro de reportes
- **Campos principales:**
  - `id` (PK): Identificador único
  - `daily_report_id` (FK): Reporte al que pertenece
  - `room_id` (FK, nullable): Sala relacionada
  - `tipo`: Tipo de entrada (observacion, incidente, mantenimiento)
  - `descripcion`: Descripción de la entrada
  - `hora`: Hora del evento
  - `severidad`: Nivel (informativo, advertencia, critico)
  - `foto`: URL de foto adjunta
  - `created_at`, `updated_at`

---

#### **MÓDULO: COMUNICACIONES**

**Tabla: novedades**
- **Descripción:** Sistema de noticias, eventos y anuncios
- **Campos principales:**
  - `id` (PK): Identificador único
  - `user_id` (FK): Usuario creador
  - `magister_id` (FK, nullable): Programa específico
  - `titulo`: Título de la novedad
  - `contenido`: Contenido completo (HTML)
  - `tipo_novedad`: Tipo (academica, administrativa, evento)
  - `imagen`: URL imagen destacada
  - `visible_publico`: Visibilidad pública
  - `roles_visibles`: Roles permitidos (JSON)
  - `es_urgente`: Flag de urgencia
  - `fecha_expiracion`: Fecha de expiración automática
  - `icono`: Icono representativo
  - `color`: Color de categoría
  - `acciones`: Botones de acción (JSON)
  - `created_at`, `updated_at`
- **Relaciones:**
  - Pertenece a: `users`, `magisters`

**Tabla: emergencies**
- **Descripción:** Alertas de emergencia activas en la plataforma
- **Campos principales:**
  - `id` (PK): Identificador único
  - `titulo`: Título de la emergencia
  - `mensaje`: Mensaje detallado
  - `tipo`: Tipo (seguridad, tecnica, climatica)
  - `activa`: Booleano de estado activo
  - `expires_at`: Fecha/hora de expiración
  - `created_by` (FK): Usuario que activó
  - `created_at`, `updated_at`

---

#### **MÓDULO: ANALYTICS Y MONITOREO**

**Tabla: page_views**
- **Descripción:** Registro de accesos a páginas de la plataforma
- **Campos principales:**
  - `id` (PK): Identificador único
  - `user_id` (FK, nullable): Usuario (null = anónimo)
  - `page_type`: Tipo de página visitada
  - `url`: URL completa
  - `method`: Método HTTP (GET, POST)
  - `ip_address`: IP del visitante
  - `user_agent`: Navegador/dispositivo
  - `session_id`: Identificador de sesión
  - `visited_at`: Timestamp de la visita
  - `created_at`, `updated_at`
- **Índices:**
  - (page_type, visited_at)
  - (user_id, visited_at)
  - (visited_at)

---

### 3.3.3 Relaciones Entre Entidades

**Resumen de relaciones clave del modelo de datos:**

1. **Jerarquía Académica:**
   ```
   Magister (1) → (N) Periods
   Magister (1) → (N) Courses
   Course (1) → (N) Clases
   Clase (1) → (N) ClaseSesion
   Period (1) → (N) Clases
   ```

2. **Asignación de Recursos:**
   ```
   Room (1) → (N) Clases
   Room (1) → (N) Incidents
   Room (1) → (N) ReportEntries
   ```

3. **Creación y Autoría:**
   ```
   User (1) → (N) Incidents
   User (1) → (N) Informes
   User (1) → (N) Novedades
   User (1) → (N) DailyReports
   User (1) → (N) PageViews
   ```

4. **Trazabilidad:**
   ```
   Incident (1) → (N) IncidentLogs
   DailyReport (1) → (N) ReportEntries
   ```

### 3.3.4 Integridad Referencial

El diseño implementa restricciones de integridad mediante:

**Claves foráneas con acciones:**
- `ON DELETE CASCADE`: Eliminación en cascada (ej: borrar magister elimina sus cursos)
- `ON DELETE SET NULL`: Establecer nulo (ej: borrar sala no elimina clases, solo desvincula)

**Índices únicos:**
- Email de usuarios y staff (evitar duplicados)
- Nombre de magisters (unicidad de programas)
- Combinación (clase_id, fecha) en sesiones (una sesión por día)

**Validaciones a nivel de base de datos:**
- Campos NOT NULL para datos obligatorios
- ENUM para valores cerrados (estado, prioridad, modalidad)
- Tipos de datos apropiados (DATE, TIME, DATETIME, TEXT, INTEGER)

---

### 3.3.5 Escalabilidad del Diseño

El modelo de datos fue diseñado considerando:

**Normalización:** 
- Tercera forma normal (3FN) para evitar redundancia
- Separación de entidades por responsabilidad única

**Flexibilidad:**
- Campos JSON para datos variables (requisitos, acciones, roles)
- Nullable en relaciones opcionales
- Polimorfismo en algunas relaciones (tokens)

**Performance:**
- Índices estratégicos en campos de búsqueda frecuente
- Timestamps para auditoría automática
- Soft deletes disponibles mediante trait de Laravel

**Migración y versionado:**
- 89 migraciones organizadas cronológicamente
- Sistema de rollback para revertir cambios
- Seeders para datos de prueba y producción

---

## Resumen

La arquitectura MVC combinada con un diseño de base de datos relacional bien estructurado proporciona una base sólida para la plataforma. La separación de responsabilidades facilita el mantenimiento, mientras que el modelo de datos normalizado garantiza consistencia e integridad. Esta arquitectura permite escalar el sistema agregando nuevos módulos sin afectar funcionalidades existentes.

---

**Nota:** Las descripciones de tablas pueden convertirse en:
- **Tablas descriptivas** con formato: Tabla | Campo | Tipo | Descripción | Restricciones
- **Diagramas ER** usando herramientas como draw.io, Lucidchart o MySQL Workbench
- **Diagramas UML** de clases mostrando relaciones entre modelos


