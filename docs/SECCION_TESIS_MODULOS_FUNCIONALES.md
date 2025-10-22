# Módulos Funcionales del Sistema

## 3.4 Descripción de Módulos Funcionales

La plataforma web se organiza en **12 módulos funcionales** independientes pero interconectados, cada uno diseñado para resolver necesidades específicas identificadas en el análisis de requerimientos. A continuación se detalla cada módulo, sus funcionalidades, usuarios objetivo y flujos de trabajo.

---

## MÓDULO 1: AUTENTICACIÓN Y GESTIÓN DE USUARIOS

### Descripción General
Sistema de autenticación segura y gestión de perfiles de usuarios con control de acceso basado en roles.

### Funcionalidades Principales

**1.1 Registro y Autenticación**
- Registro de nuevos usuarios con validación de email
- Inicio de sesión con credenciales (email/contraseña)
- Recuperación de contraseña mediante enlace por email
- Verificación de email para activación de cuenta
- Cierre de sesión seguro

**1.2 Gestión de Perfiles**
- Visualización y edición de datos personales
- Carga de foto de perfil (Cloudinary)
- Cambio de contraseña
- Preferencias de visualización
- Historial de último acceso

**1.3 Sistema de Roles y Permisos**

| Rol | Nivel de Acceso | Permisos Principales |
|-----|----------------|---------------------|
| **Administrador** | Total | Gestión completa del sistema, configuraciones globales |
| **Director Administrativo** | Alto | Gestión operativa, reportes, incidencias, personal |
| **Director de Programa** | Medio | Gestión de su magíster, cursos, clases, estudiantes |
| **Asistente de Postgrado** | Medio | Soporte administrativo, gestión de clases y calendario |
| **Estudiante** | Bajo | Consulta de calendario, cursos, informes, novedades |
| **Invitado** | Público | Solo lectura de información pública |

### Usuarios Objetivo
Todos los usuarios del sistema (administrativos, académicos, estudiantes)

### Tecnologías Utilizadas
- Laravel Breeze (autenticación web)
- Laravel Sanctum (autenticación API)
- Middleware de verificación de roles
- Hashing bcrypt para contraseñas

---

## MÓDULO 2: GESTIÓN DE PROGRAMAS ACADÉMICOS (MAGISTERS)

### Descripción General
Administración de los programas de magíster ofrecidos por la facultad, con información completa de cada programa.

### Funcionalidades Principales

**2.1 Registro de Programas**
- Creación de nuevos programas de magíster
- Asignación de información básica (nombre, descripción)
- Designación de director del programa
- Datos de contacto (email, teléfono, asistente)

**2.2 Personalización Visual**
- Asignación de color identificatorio (código hexadecimal)
- Orden de visualización en listados
- Logo o imagen representativa (futuro)

**2.3 Gestión de Cohortes**
- Diferenciación por año de ingreso (2024, 2025, 2026, etc.)
- Seguimiento de generaciones de estudiantes
- Períodos académicos específicos por cohorte

**2.4 Consultas y Reportes**
- Vista pública de programas disponibles
- Cantidad de cursos por programa
- Estadísticas de matrícula por cohorte
- Listado de estudiantes por programa

### Usuarios Objetivo
- **Administradores**: Creación y gestión completa
- **Directores de Programa**: Consulta y edición de su programa
- **Estudiantes/Público**: Consulta de información

### Modelo de Datos
- Tabla: `magisters`
- Relaciones: `periods`, `courses`, `users`, `novedades`, `informes`

---

## MÓDULO 3: GESTIÓN DE PERÍODOS Y TRIMESTRES

### Descripción General
Administración de períodos académicos (trimestres) con fechas específicas, organizados por programa y año de ingreso.

### Funcionalidades Principales

**3.1 Creación de Trimestres**
- Definición de fechas inicio y fin
- Asignación a programa de magíster
- Especificación de número de trimestre (1, 2, 3)
- Año académico del programa (1° o 2° año)
- Año de ingreso de la cohorte

**3.2 Calendario Académico**
- Visualización de períodos activos
- Identificación de períodos pasados y futuros
- Detección de traslapes entre períodos
- Cálculo de días hábiles del trimestre

**3.3 Gestión Temporal**
- Activación/desactivación de períodos
- Edición de fechas
- Eliminación con validación (no permitir si hay clases asignadas)

### Usuarios Objetivo
- **Administradores y Asistentes**: Creación y gestión
- **Directores de Programa**: Consulta de períodos de su magíster
- **Todos**: Consulta de calendario académico

### Modelo de Datos
- Tabla: `periods`
- Relaciones: `magisters`, `clases`

### Reglas de Negocio
- Un trimestre debe tener fecha_fin > fecha_inicio
- No puede haber traslape de trimestres del mismo magíster
- Solo se pueden eliminar trimestres sin clases asignadas

---

## MÓDULO 4: GESTIÓN DE CURSOS Y ASIGNATURAS

### Descripción General
Catálogo de cursos/asignaturas que conforman la malla curricular de cada programa de magíster.

### Funcionalidades Principales

**4.1 Registro de Cursos**
- Nombre y código del curso
- Descripción detallada del contenido
- Profesor(es) responsable(s)
- Créditos SCT (Sistema de Créditos Transferibles)
- Tipo de curso (obligatorio, electivo, taller)
- Prerequisitos (JSON con cursos previos requeridos)

**4.2 Asignación a Programas**
- Vinculación con programa de magíster específico
- Organización por año académico
- Malla curricular completa del programa

**4.3 Consultas Públicas**
- Vista pública de cursos por programa
- Filtrado por año de ingreso
- Información del profesor
- Años disponibles de dictado

### Usuarios Objetivo
- **Administradores y Directores**: Creación y gestión
- **Estudiantes**: Consulta de cursos disponibles
- **Público**: Información de malla curricular

### Modelo de Datos
- Tabla: `courses`
- Relaciones: `magisters`, `clases`

---

## MÓDULO 5: GESTIÓN DE CLASES Y SESIONES

### Descripción General
Módulo central que programa y organiza las instancias específicas de cursos en trimestres determinados, incluyendo horarios, salas y sesiones individuales.

### Funcionalidades Principales

**5.1 Programación de Clases**
- Asignación de curso a trimestre específico
- Definición de horario (día, hora inicio, hora fin)
- Asignación de sala física
- Modalidad de dictado:
  - **Presencial**: En sala física
  - **Online**: Vía Zoom/Meet
  - **Híbrida**: Combinación de ambas
- URL de Zoom/Meet para clases virtuales

**5.2 Gestión de Sesiones Individuales**
- Generación automática de sesiones basadas en horario de clase
- Registro de fecha específica de cada sesión
- Estados de sesión:
  - **Pendiente**: No realizada aún
  - **Completada**: Clase realizada
  - **Cancelada**: Clase suspendida
- Observaciones por sesión
- Enlace a grabación de la clase (si aplica)

**5.3 Sistema de Bloques Horarios y Descansos**
- **Coffee Break**: Descanso corto (ej: 10:30-11:00)
- **Lunch Break**: Almuerzo (ej: 13:30-14:30)
- Visualización en calendario como bloques separados
- Aplicación automática en clases extensas (sábados)

**5.4 Disponibilidad de Salas**
- Consulta de salas libres en horario específico
- Detección de conflictos de asignación
- Sugerencias de salas disponibles

**5.5 Exportación de Datos**
- Exportación de clases a Excel/CSV
- Reportes de programación por trimestre
- Listados por profesor, sala o programa

### Usuarios Objetivo
- **Administradores y Asistentes**: Programación completa
- **Directores de Programa**: Gestión de clases de su magíster
- **Estudiantes**: Consulta de horarios

### Modelo de Datos
- Tablas: `clases`, `clase_sesiones`
- Relaciones: `courses`, `periods`, `rooms`

### Reglas de Negocio
- Una clase no puede tener dos sesiones el mismo día
- Una sala no puede tener dos clases al mismo horario
- Si modalidad es online, la sala es opcional
- Coffee/Lunch breaks solo en clases de más de 4 horas

---

## MÓDULO 6: CALENDARIO ACADÉMICO INTERACTIVO

### Descripción General
Visualización centralizada de todas las actividades académicas en formato calendario interactivo con múltiples vistas.

### Funcionalidades Principales

**6.1 Vistas del Calendario**
- **Vista Mensual**: Resumen del mes completo
- **Vista Semanal**: Detalle hora por hora de la semana
- **Vista de Agenda**: Lista cronológica de eventos

**6.2 Tipos de Eventos Mostrados**
- **Sesiones de Clase**: Con color según programa
- **Coffee Breaks**: Bloques naranjas
- **Lunch Breaks**: Bloques rojos
- **Eventos Especiales**: Fechas importantes (futuro)

**6.3 Filtros Avanzados**
- Por programa de magíster
- Por año de ingreso (cohorte)
- Por fecha (rango personalizado)
- Por sala física
- Por modalidad (presencial/online)

**6.4 Información de Eventos (Modal)**
- Título de la clase o evento
- Programa y curso
- Profesor
- Horario completo
- Sala asignada
- Modalidad
- Enlace Zoom (si aplica)
- Enlace a grabación (si existe)
- Botón para ver detalles completos

**6.5 Calendario Público vs Administrativo**
- **Calendario Público** (`/Calendario-Academico`):
  - Sin autenticación requerida
  - Solo lectura
  - Información básica de clases
  
- **Calendario Administrativo** (`/calendario`):
  - Requiere autenticación
  - Información completa
  - Enlaces funcionales

### Usuarios Objetivo
- **Todos los usuarios**: Consulta de calendario
- **Público general**: Calendario público
- **Personal autorizado**: Calendario administrativo

### Tecnologías Utilizadas
- **FullCalendar**: Librería JavaScript de calendario
- **Alpine.js**: Interactividad de modales
- **Tailwind CSS**: Estilos responsive

---

## MÓDULO 7: GESTIÓN DE SALAS Y RECURSOS

### Descripción General
Administración de espacios físicos (salas) con información de capacidad, ubicación y equipamiento disponible.

### Funcionalidades Principales

**7.1 Registro de Salas**
- Nombre/código de sala (ej: "A301", "Auditorio Principal")
- Ubicación (edificio, piso)
- Capacidad de personas
- Descripción general
- Equipamiento disponible (JSON):
  - Proyector
  - Pizarra
  - Computador
  - Sistema de audio
  - Aire acondicionado
  - Conexión HDMI
  - Otros

**7.2 Consultas de Disponibilidad**
- Vista de salas con clases asignadas
- Búsqueda de salas libres por horario
- Filtrado por capacidad mínima
- Filtrado por equipamiento requerido

**7.3 Vista Pública de Salas**
- Directorio de salas disponibles
- Información de ubicación y capacidad
- Equipamiento por sala

### Usuarios Objetivo
- **Administradores**: Gestión completa
- **Asistentes**: Asignación de salas a clases
- **Todos**: Consulta de información de salas

### Modelo de Datos
- Tabla: `rooms`
- Relaciones: `clases`, `incidents`, `report_entries`

---

## MÓDULO 8: GESTIÓN DE PERSONAL (STAFF)

### Descripción General
Directorio del equipo académico y administrativo de la facultad con información de contacto y perfiles.

### Funcionalidades Principales

**8.1 Registro de Personal**
- Nombre completo
- Cargo o posición
- Email institucional
- Teléfono de contacto
- Anexo telefónico interno
- Biografía o descripción profesional
- Foto de perfil (Cloudinary)

**8.2 Organización**
- Categorización por tipo (académico, administrativo, directivo)
- Asociación con programas de magíster
- Ordenamiento personalizado

**8.3 Vista Pública (Directorio)**
- Tarjetas de presentación del equipo
- Información de contacto
- Diseño tipo "Equipo FEN"
- Responsive y con fotos

### Usuarios Objetivo
- **Administradores**: Gestión completa
- **Todos**: Consulta de directorio
- **Público**: Vista pública del equipo

### Modelo de Datos
- Tabla: `staff`
- Sin relaciones directas (entidad independiente)

---

## MÓDULO 9: SISTEMA DE INCIDENCIAS

### Descripción General
Sistema de tickets para reportar y gestionar problemas técnicos, de infraestructura o administrativos.

### Funcionalidades Principales

**9.1 Reporte de Incidencias**
- Título descriptivo del problema
- Descripción detallada
- Sala afectada (opcional)
- Programa relacionado (opcional)
- Prioridad:
  - **Baja**: Problemas menores
  - **Media**: Requiere atención próxima
  - **Alta**: Urgente
  - **Crítica**: Requiere atención inmediata
- Adjuntar foto del problema (Cloudinary)
- Generación automática de número de ticket

**9.2 Gestión de Tickets**
- Estados:
  - **Pendiente**: Recién reportada
  - **En Proceso**: Siendo atendida
  - **Resuelta**: Problema solucionado
  - **Cerrada**: Validada por reportador
- Asignación a responsable
- Registro de fecha/hora de resolución
- Comentario de resolución
- Historial completo de cambios (logs)

**9.3 Estadísticas de Incidencias**
- Tiempo promedio de resolución
- Incidencias por sala
- Incidencias por programa
- Incidencias por prioridad
- Tendencias mensuales

**9.4 Filtros y Búsquedas**
- Por estado
- Por prioridad
- Por sala
- Por programa
- Por fecha
- Por usuario reportador

### Usuarios Objetivo
- **Todos**: Pueden reportar incidencias
- **Administradores y Personal**: Gestión y resolución
- **Directores**: Estadísticas de su programa

### Modelo de Datos
- Tablas: `incidents`, `incident_logs`
- Relaciones: `users` (reportador y resolutor), `rooms`, `magisters`

### Flujo de Trabajo
1. Usuario reporta problema → Estado: Pendiente
2. Administrador asigna a responsable → Estado: En Proceso
3. Responsable resuelve → Estado: Resuelta (registra fecha y comentario)
4. Usuario valida → Estado: Cerrada

---

## MÓDULO 10: GESTIÓN DE INFORMES Y DOCUMENTOS

### Descripción General
Repositorio centralizado de documentos oficiales (calendarios académicos, manuales, reglamentos, etc.) con control de visibilidad.

### Funcionalidades Principales

**10.1 Subida de Documentos**
- Upload de archivos (PDF, Word, Excel, etc.)
- Nombre descriptivo del documento
- Descripción del contenido
- Categorización por tipo:
  - **Académico**: Calendarios, programas de estudio
  - **Administrativo**: Reglamentos, procedimientos
  - **Calendario**: Fechas importantes
  - **Manual**: Guías y tutoriales
  - **Otro**: Documentos varios

**10.2 Control de Acceso**
- **Visibilidad pública**: Accesible sin autenticación
- **Visibilidad privada**: Solo usuarios autenticados
- Asignación a programa específico (o todos)

**10.3 Búsqueda de Documentos**
- Por nombre o descripción
- Por tipo de documento
- Por programa
- Por usuario que subió

**10.4 Almacenamiento**
- Cloudinary para archivos en la nube
- Respaldo automático
- URLs permanentes

### Usuarios Objetivo
- **Administradores y Directores**: Subida de documentos
- **Todos**: Descarga de documentos autorizados
- **Público**: Descarga de documentos públicos

### Modelo de Datos
- Tabla: `informes`
- Relaciones: `users`, `magisters`

---

## MÓDULO 11: SISTEMA DE NOVEDADES Y COMUNICACIONES

### Descripción General
Canal oficial para publicar noticias, eventos, anuncios y comunicados institucionales.

### Funcionalidades Principales

**11.1 Creación de Novedades**
- Título llamativo
- Contenido completo (editor de texto enriquecido)
- Imagen destacada
- Tipo de novedad:
  - **Académica**: Eventos, seminarios, charlas
  - **Administrativa**: Cambios, procedimientos
  - **Evento**: Fechas importantes
  - **Urgente**: Comunicados importantes
- Color de categoría
- Icono representativo

**11.2 Control de Visibilidad**
- **Visibilidad pública**: Todos pueden ver
- **Visibilidad por roles**: Solo roles específicos
- Asignación a programa (o todos)

**11.3 Sistema de Expiración**
- Fecha de expiración automática
- Novedades activas vs expiradas
- Ocultamiento automático al expirar

**11.4 Acciones Personalizadas**
- Botones de acción (JSON):
  - Enlace a formulario
  - Descarga de archivo
  - Redirección a página

**11.5 Filtros**
- Por tipo de novedad
- Por programa
- Por estado (activa/expirada)
- Por búsqueda de texto

### Usuarios Objetivo
- **Administradores y Directores**: Creación y gestión
- **Todos**: Lectura de novedades
- **Público**: Lectura de novedades públicas

### Modelo de Datos
- Tabla: `novedades`
- Relaciones: `users`, `magisters`

---

## MÓDULO 12: REPORTES DIARIOS Y BITÁCORAS

### Descripción General
Sistema de documentación diaria de actividades, observaciones y eventos en las instalaciones de la facultad.

### Funcionalidades Principales

**12.1 Creación de Reportes Diarios**
- Fecha del reporte
- Título general del día
- Múltiples entradas por reporte

**12.2 Entradas de Reporte**
- Sala o ubicación relacionada
- Tipo de entrada:
  - **Observación**: Notas generales
  - **Incidente**: Problema menor
  - **Mantenimiento**: Trabajo realizado
- Descripción detallada
- Hora del evento
- Severidad:
  - **Informativo**: FYI
  - **Advertencia**: Requiere atención
  - **Crítico**: Problema serio
- Foto adjunta (opcional)

**12.3 Generación de PDF**
- Documento PDF automático con todas las entradas
- Logo institucional
- Formato profesional
- Descarga y almacenamiento

**12.4 Distribución**
- Envío automático por email
- Notificación a responsables
- Archivo histórico

### Usuarios Objetivo
- **Personal Administrativo**: Creación de reportes
- **Directores y Administradores**: Revisión y consulta

### Modelo de Datos
- Tablas: `daily_reports`, `report_entries`
- Relaciones: `users`, `rooms`

---

## MÓDULO 13: SISTEMA DE EMERGENCIAS

### Descripción General
Protocolo de comunicación para situaciones críticas que requieren difusión inmediata en toda la plataforma.

### Funcionalidades Principales

**13.1 Activación de Emergencias**
- Título de la emergencia
- Mensaje detallado con instrucciones
- Tipo:
  - **Seguridad**: Evacuación, amenaza
  - **Técnica**: Caída de sistemas, corte de luz
  - **Climática**: Condiciones adversas
- Fecha/hora de expiración

**13.2 Difusión en Plataforma**
- **Banner visible** en todas las páginas
- Color llamativo (rojo/naranja)
- Icono de alerta
- No se puede cerrar hasta que expire

**13.3 Gestión de Estado**
- Activación inmediata
- Desactivación manual
- Expiración automática por tiempo

### Usuarios Objetivo
- **Administradores**: Activación y gestión
- **Todos**: Visualización de alertas activas

### Modelo de Datos
- Tabla: `emergencies`
- Relaciones: `users` (creador)

---

## MÓDULO 14: SISTEMA DE ANALYTICS Y ESTADÍSTICAS

### Descripción General
Monitoreo del uso de la plataforma y cálculo de métricas clave para toma de decisiones.

### Funcionalidades Principales

**14.1 Tracking de Accesos**
- Registro automático de visitas a páginas específicas
- Diferenciación entre usuarios autenticados y anónimos
- Información capturada:
  - IP del visitante
  - Navegador/dispositivo (user agent)
  - Timestamp de acceso
  - Session ID

**14.2 Métricas Calculadas**
- **Número de accesos mensuales**: Total de visitas
- **Sesiones únicas**: Usuarios distintos
- **Páginas más visitadas**: Top 10
- **Distribución usuarios**: Registrados vs anónimos
- **Accesos al calendario**: Público vs administrativo
- **Tiempo promedio de resolución de incidencias**
- **Porcentaje de utilización del calendario académico**

**14.3 Dashboard de Estadísticas**
- KPIs principales en tarjetas
- Gráficos de tendencias mensuales (Chart.js)
- Tablas de top páginas
- Filtros por mes, año, programa

**14.4 API de Estadísticas**
- Endpoint JSON para obtener métricas
- Consumo desde aplicaciones externas
- Actualización en tiempo real

### Usuarios Objetivo
- **Administradores**: Acceso completo a analytics
- **Directores**: Estadísticas de su programa

### Modelo de Datos
- Tabla: `page_views`
- Relaciones: `users`

### Tecnologías Utilizadas
- Middleware `TrackPageViews`
- Chart.js para visualizaciones
- Consultas SQL optimizadas con índices

---

## MÓDULO 15: API REST COMPLETA

### Descripción General
Interfaz de programación (API) que permite consumir todas las funcionalidades de la plataforma desde aplicaciones externas (especialmente móviles).

### Funcionalidades Principales

**15.1 Endpoints Públicos (Sin Autenticación)**
- `GET /api/public/magisters`: Listado de programas
- `GET /api/public/courses`: Catálogo de cursos
- `GET /api/public/events`: Calendario académico
- `GET /api/public/staff`: Directorio de personal
- `GET /api/public/rooms`: Listado de salas
- `GET /api/public/novedades`: Novedades públicas
- `GET /api/public/informes`: Documentos públicos

**15.2 Endpoints Protegidos (Con Token Sanctum)**
- Todos los recursos con CRUD completo:
  - `/api/users`
  - `/api/magisters`
  - `/api/courses`
  - `/api/clases`
  - `/api/periods`
  - `/api/rooms`
  - `/api/incidents`
  - `/api/informes`
  - `/api/novedades`
  - `/api/staff`
  - `/api/daily-reports`

**15.3 Filtros Avanzados**
- Por año de ingreso (cohorte)
- Por programa de magíster
- Por trimestre
- Por búsqueda de texto
- Por fechas (rangos)
- Por tipo/categoría
- Por estado

**15.4 Características Técnicas**
- **Paginación**: Resultados limitados por página
- **Formato JSON**: Respuestas estandarizadas
- **Códigos HTTP**: Correctos (200, 201, 400, 401, 404, 500)
- **Validación**: Robusta en todos los endpoints
- **CORS**: Configurado para aplicaciones móviles

**15.5 Autenticación API**
- Registro: `POST /api/register`
- Login: `POST /api/login` (retorna token)
- Logout: `POST /api/logout`
- Token en header: `Authorization: Bearer {token}`

### Usuarios Objetivo
- **Desarrolladores**: Integración con apps móviles
- **Aplicaciones externas**: Consumo de datos

### Calidad de la API
- ✅ 16 controladores API
- ✅ 23 filtros implementados
- ✅ Auditoría completa realizada
- ✅ Sincronización 100% con vistas web
- ✅ 0 errores de código

---

## Resumen Comparativo de Módulos

| # | Módulo | Tablas BD | Vistas | Controladores | Nivel Complejidad |
|---|--------|-----------|--------|---------------|-------------------|
| 1 | Autenticación | 3 | 8 | 9 | Alta |
| 2 | Magisters | 1 | 4 | 2 | Media |
| 3 | Períodos | 1 | 4 | 2 | Media |
| 4 | Cursos | 1 | 4 | 2 | Media |
| 5 | Clases/Sesiones | 2 | 6 | 2 | Alta |
| 6 | Calendario | 0 | 2 | 3 | Alta |
| 7 | Salas | 1 | 4 | 2 | Baja |
| 8 | Personal | 1 | 4 | 2 | Baja |
| 9 | Incidencias | 2 | 5 | 2 | Media |
| 10 | Informes | 1 | 4 | 2 | Media |
| 11 | Novedades | 1 | 4 | 2 | Media |
| 12 | Reportes Diarios | 2 | 3 | 1 | Media |
| 13 | Emergencias | 1 | 1 | 1 | Baja |
| 14 | Analytics | 1 | 1 | 1 | Media |
| 15 | API REST | - | - | 16 | Alta |

**TOTAL: 18 tablas, 151 vistas, 49 controladores**

---

## Interconexión Entre Módulos

Los módulos no funcionan de manera aislada, sino que están interconectados:

```
Magisters
    ↓
  Periods ← Courses
    ↓         ↓
    Clases (combina período + curso)
      ↓
  ClaseSesiones → Calendario
      ↓
    Rooms (asignación)
      ↓
  Incidents (reportes de problemas)

Users (creadores)
  ↓
Novedades, Informes, DailyReports, Incidents

Analytics (monitorea todo)
```

---

## Conclusión

La plataforma integra **15 módulos funcionales** que cubren todas las necesidades identificadas en la gestión académica y administrativa de los programas de magíster. Cada módulo fue diseñado con interfaz intuitiva, validaciones robustas y permisos apropiados según roles de usuario. La arquitectura modular permite agregar funcionalidades futuras sin afectar módulos existentes.

---

**Nota para la tesis:** Esta descripción de módulos puede complementarse con:
- Capturas de pantalla de cada módulo
- Diagramas de flujo de procesos clave
- Casos de uso específicos por módulo
- Métricas de uso reales (si ya está en producción)


