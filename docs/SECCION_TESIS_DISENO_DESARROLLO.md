# Diseño y Desarrollo de la Plataforma Web

## 3.3 Diseño y desarrollo de la plataforma web

A partir de las problemáticas identificadas en la gestión académica y administrativa de los programas de magíster de la Facultad de Economía y Negocios, se desarrolló una plataforma web integral basada en el framework **Laravel 12** para el backend y **Tailwind CSS** para el diseño de interfaz. El proceso de desarrollo siguió una metodología ágil con enfoque incremental, lo que permitió entregar avances funcionales y validar cada módulo con los usuarios finales.

### 3.3.1 Arquitectura tecnológica

La plataforma se construyó sobre una arquitectura moderna de tres capas que separa las responsabilidades y permite escalabilidad:

**Backend (Capa de Aplicación):**
- **Laravel 12** (PHP 8.2) como framework principal, proporcionando robustez, seguridad y facilidad de mantenimiento
- **SQLite** como base de datos relacional, ofreciendo simplicidad para el entorno de desarrollo y facilidad de respaldo
- **Laravel Sanctum** para autenticación de API, permitiendo la integración con aplicaciones móviles
- **Cloudinary** para gestión y almacenamiento de archivos multimedia en la nube
- **DomPDF** para generación dinámica de reportes en formato PDF

**Frontend (Capa de Presentación):**
- **Tailwind CSS** como framework de diseño, garantizando interfaces modernas, responsivas y consistentes
- **Alpine.js** para interactividad ligera sin la complejidad de frameworks pesados
- **Vite** como bundler modular para optimización de assets
- **FullCalendar** para visualización avanzada de calendarios académicos
- **SweetAlert2** para alertas y notificaciones amigables al usuario

**API RESTful (Capa de Integración):**
- Endpoints públicos para consulta de información académica sin autenticación
- Endpoints protegidos con token Sanctum para operaciones CRUD por usuarios autenticados
- Arquitectura que permite consumo desde aplicaciones móviles o servicios externos

### 3.3.2 Módulos funcionales desarrollados

La plataforma se estructuró en módulos independientes pero interconectados, cada uno respondiendo a necesidades específicas identificadas:

#### 1. Gestión de Programas Académicos (Magisters)
Permite administrar la información de cada programa de magíster, incluyendo:
- Datos generales del programa (nombre, descripción, director)
- Cohortes por año de ingreso para diferenciar generaciones de estudiantes
- Vinculación con cursos y períodos académicos específicos

#### 2. Gestión de Cursos y Clases
Sistema centralizado para organizar la oferta académica:
- Registro de cursos con información de profesores, trimestres y modalidad (presencial/online)
- Gestión de sesiones de clase con horarios, salas y bloques de descanso
- Integración con **Google Meet/Zoom** para clases virtuales
- Sistema de grabaciones de clases con enlaces de acceso

#### 3. Calendario Académico Interactivo
Visualización centralizada de toda la actividad académica:
- **Vista mensual, semanal y de agenda** para diferentes necesidades de consulta
- Diferenciación visual por programa mediante código de colores
- Bloques de **coffee break** y **lunch break** en sesiones extensas
- Calendario público accesible sin autenticación para estudiantes y público general
- Calendario administrativo con funcionalidades completas para personal autorizado

#### 4. Sistema de Salas y Recursos
Control de espacios físicos y equipamiento:
- Registro de salas con capacidad, ubicación y equipamiento disponible
- Visualización de disponibilidad en tiempo real
- Prevención de conflictos de asignación de espacios

#### 5. Gestión de Personal (Staff)
Directorio completo del equipo académico y administrativo:
- Perfiles con fotografía, cargo, contacto y descripción
- Vista pública tipo "directorio" para fácil consulta por estudiantes
- Gestión de roles y permisos para control de acceso

#### 6. Sistema de Informes y Documentos
Repositorio centralizado de documentación oficial:
- Categorización por tipo (académicos, administrativos, calendarios, etc.)
- Upload de archivos a **Cloudinary** con respaldo automático
- Control de visibilidad (público/privado)
- Búsqueda avanzada por nombre, descripción y tipo

#### 7. Sistema de Novedades y Comunicaciones
Canal oficial de comunicación institucional:
- Publicación de noticias, eventos y anuncios
- Sistema de expiración automática de novedades
- Filtrado por programa para comunicación dirigida
- Diferenciación entre novedades académicas, administrativas y eventos

#### 8. Gestión de Incidencias
Sistema de tickets para resolución de problemas:
- Registro de incidencias con prioridad y estado
- Asignación a responsables para seguimiento
- Métricas de tiempo promedio de resolución
- Historial completo de acciones tomadas

#### 9. Reportes Diarios
Documentación de actividades y observaciones:
- Generación de reportes en formato PDF
- Adjuntos multimedia
- Distribución automática por email

#### 10. Sistema de Emergencias
Protocolo de comunicación en situaciones críticas:
- Activación de alertas visibles en toda la plataforma
- Difusión inmediata de información importante
- Control de vigencia de emergencias activas

### 3.3.3 Sistema de Analytics y Monitoreo

Para evaluar el uso y efectividad de la plataforma, se implementó un **sistema de analytics** que registra y analiza métricas clave:

**Métricas implementadas:**
- **Número de accesos**: Contabilización de visitas diferenciando usuarios autenticados y anónimos
- **Páginas más visitadas**: Identificación de secciones con mayor demanda
- **Tiempo promedio de resolución de incidencias**: Medición de eficiencia del equipo de soporte
- **Porcentaje de utilización del calendario académico**: Evaluación del uso eficiente de períodos lectivos
- **Tendencias mensuales**: Análisis de patrones de uso a lo largo del año

El sistema utiliza un **middleware de tracking** que registra automáticamente accesos a páginas específicas sin afectar el rendimiento, almacenando información como IP, navegador, sesión y timestamp para análisis posteriores.

### 3.3.4 API RESTful para integración móvil

Se desarrolló una **API completa** que replica la funcionalidad de la plataforma web, permitiendo su consumo desde aplicaciones móviles:

**Características principales:**
- **Endpoints públicos** (sin autenticación): Consulta de calendarios, cursos, programas, personal, informes y novedades
- **Endpoints protegidos** (con token Sanctum): Operaciones CRUD para usuarios autenticados
- **Filtros avanzados**: Por año de ingreso, programa, trimestre, tipo, fecha, búsqueda de texto, etc.
- **Paginación**: Para optimizar transferencia de datos en conexiones móviles
- **Formato JSON estandarizado**: Respuestas consistentes y documentadas

La API pasó por una **auditoría exhaustiva** que garantizó:
- Sincronización 100% entre funcionalidad web y API
- 23 filtros adicionales implementados para paridad con vistas web
- Corrección de rutas duplicadas y dependencias obsoletas
- Validación de permisos y roles

### 3.3.5 Sistema de autenticación y autorización

La seguridad se implementó mediante un **sistema de roles y permisos multinivel**:

**Roles principales:**
- **Administrador**: Acceso total al sistema
- **Director Administrativo**: Gestión operativa completa
- **Director de Programa**: Gestión específica de su magíster
- **Asistente de Postgrado**: Soporte administrativo
- **Estudiante**: Acceso limitado a consulta
- **Invitado**: Solo lectura de información pública

**Mecanismos de seguridad:**
- Autenticación web con **Laravel Breeze** (sesiones)
- Autenticación API con **Laravel Sanctum** (tokens)
- Middleware de verificación de roles en todas las rutas protegidas
- Hashing de contraseñas con **bcrypt**
- Protección CSRF en formularios

### 3.3.6 Testing y calidad del código

Se implementó una estrategia de testing automatizado utilizando **Pest PHP**:

**Tipos de tests desarrollados:**
- **Tests unitarios**: Validación de lógica de negocio en modelos
- **Tests de integración**: Verificación de endpoints API
- **Tests de feature**: Simulación de flujos completos de usuario

El código se mantiene con estándares de calidad mediante:
- **Laravel Pint** para formateo automático según PSR-12
- **PHPStan** para análisis estático de código
- Scripts batch automatizados para ejecución rápida de suites de tests

### 3.3.7 Diseño de interfaz y experiencia de usuario

El diseño siguió principios de **UX/UI modernos**:

**Características de diseño:**
- **Responsive design**: Adaptación automática a móviles, tablets y escritorio
- **Modo oscuro**: Soporte completo en toda la plataforma
- **Componentes reutilizables**: Consistencia visual en todas las vistas
- **Navegación intuitiva**: Menús contextuales según rol del usuario
- **Feedback visual inmediato**: Validaciones en tiempo real, alertas amigables
- **Accesibilidad**: Uso de etiquetas semánticas y contraste adecuado

El uso de **Tailwind CSS** permitió desarrollo ágil sin sacrificar personalización, mientras que **Alpine.js** proporcionó interactividad ligera sin la complejidad de frameworks JavaScript pesados.

### 3.3.8 Despliegue y documentación

La plataforma se documentó exhaustivamente con:
- **47 documentos técnicos** en la carpeta `/docs`
- Guías de testing y uso de API
- Documentación de cambios y migraciones
- Análisis de componentes y sistemas

El despliegue se facilitó mediante:
- Scripts automatizados para migración de base de datos
- Seeders con datos de prueba realistas
- Comandos personalizados de Artisan para tareas administrativas

---

## 3.3.9 Resultados del desarrollo

El desarrollo resultó en una plataforma:

✅ **Funcional**: 16 controladores API, 89 migraciones, 151 vistas blade  
✅ **Escalable**: Arquitectura modular que permite agregar funcionalidades  
✅ **Mantenible**: Código limpio siguiendo estándares Laravel  
✅ **Documentada**: 47 documentos técnicos de referencia  
✅ **Segura**: Sistema robusto de autenticación y autorización  
✅ **Testeada**: Suite de tests automatizados  
✅ **Integrable**: API RESTful completa para aplicaciones móviles  

El proceso de desarrollo iterativo permitió validación continua con usuarios reales, asegurando que cada funcionalidad respondiera efectivamente a las necesidades identificadas en el análisis inicial.

---

**Nota:** Este documento describe la arquitectura y desarrollo de la plataforma web para la Facultad de Economía y Negocios, desarrollada entre 2024-2025.


