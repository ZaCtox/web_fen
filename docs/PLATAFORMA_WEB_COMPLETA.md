# 🌐 Plataforma Web FEN - Documentación Completa

## 📅 Fecha: Octubre 2025

## 🎯 Descripción General

**Sistema de Gestión Académica Web FEN** es una plataforma web integral desarrollada para la Facultad de Economía y Negocios (FEN) de la Universidad de Talca. El sistema proporciona herramientas completas para la gestión académica, administrativa y de recursos, con una arquitectura moderna y escalable.

---

## 🏗️ Arquitectura Técnica

### **Stack Tecnológico**
- **Backend:** Laravel 10 (PHP 8.1+)
- **Frontend:** Blade Templates + Alpine.js + Tailwind CSS
- **Base de Datos:** SQLite (desarrollo) / MySQL (producción)
- **API:** RESTful con Laravel Sanctum
- **Autenticación:** Multi-rol con permisos granulares
- **Testing:** Pest PHP + Feature Tests

### **Patrón Arquitectónico**
- **MVC (Model-View-Controller)** implementado con Laravel
- **API-First Design** para integración móvil
- **Component-Based Frontend** con Blade Components
- **Service Layer** para lógica de negocio compleja

---

## 👥 Sistema de Roles y Permisos

### **Roles Principales**
1. **Administrador** - Control total del sistema
2. **Director Administrativo** - Gestión administrativa
3. **Decano** - Supervisión académica
4. **Docente** - Gestión de clases y cursos
5. **Administrativo** - Soporte administrativo
6. **Asistente de Postgrado** - Gestión de programas
7. **Visor** - Solo lectura

### **Permisos por Módulo**
- **Usuarios:** Solo Administrador
- **Magisters:** Administrador, Director Administrativo, Asistente de Postgrado
- **Cursos:** Administrador, Director Administrativo, Docente
- **Clases:** Administrador, Director Administrativo, Docente
- **Salas:** Administrador, Director Administrativo, Administrativo
- **Incidencias:** Todos los roles (según tipo)
- **Reportes Diarios:** Administrador, Director Administrativo, Administrativo

---

## 📱 Módulos Principales del Sistema

### **1. 🏠 Dashboard Principal**
- **Descripción:** Panel de control central con métricas clave
- **Funcionalidades:**
  - Resumen de actividades del día
  - Estadísticas de incidencias
  - Calendario de eventos próximos
  - Accesos rápidos a módulos principales
- **Usuarios:** Todos los roles autenticados

### **2. 👥 Gestión de Usuarios**
- **Descripción:** Administración completa de usuarios del sistema
- **Funcionalidades:**
  - CRUD de usuarios
  - Asignación de roles
  - Gestión de permisos
  - Validación de emails
- **Usuarios:** Solo Administrador

### **3. 🎓 Programas de Magíster**
- **Descripción:** Gestión de programas académicos de postgrado
- **Funcionalidades:**
  - CRUD de magisters
  - Configuración de colores identificativos
  - Gestión de estados (activo/inactivo)
  - Relación con cursos
- **Usuarios:** Administrador, Director Administrativo, Asistente de Postgrado

### **4. 📚 Gestión de Cursos**
- **Descripción:** Administración de cursos por programa
- **Funcionalidades:**
  - CRUD de cursos
  - Asignación a magisters
  - Gestión de códigos únicos
  - Filtros por programa y año
- **Usuarios:** Administrador, Director Administrativo, Docente

### **5. 🏫 Gestión de Clases**
- **Descripción:** Administración de sesiones de clase
- **Funcionalidades:**
  - CRUD de clases
  - Gestión de sesiones
  - Asignación de salas
  - Control de horarios
  - Sistema de bloques horarios
- **Usuarios:** Administrador, Director Administrativo, Docente

### **6. 🏢 Gestión de Salas**
- **Descripción:** Administración de espacios físicos
- **Funcionalidades:**
  - CRUD de salas
  - Gestión de tipos y capacidades
  - Control de disponibilidad
  - Filtros por características
- **Usuarios:** Administrador, Director Administrativo, Administrativo

### **7. 📅 Calendario Académico**
- **Descripción:** Gestión de eventos y actividades académicas
- **Funcionalidades:**
  - CRUD de eventos
  - Vista de calendario
  - Filtros por tipo y fecha
  - Integración con clases
- **Usuarios:** Todos los roles autenticados

### **8. 🚨 Sistema de Incidencias**
- **Descripción:** Gestión de problemas y reportes
- **Funcionalidades:**
  - Creación de incidencias
  - Upload de evidencias
  - Sistema de prioridades
  - Estados de resolución
  - Estadísticas avanzadas
- **Usuarios:** Todos los roles (según tipo)

### **9. 📊 Reportes Diarios**
- **Descripción:** Sistema de reportes operativos diarios
- **Funcionalidades:**
  - Creación de reportes
  - Escalas de severidad
  - Generación de PDFs
  - Historial de reportes
- **Usuarios:** Administrador, Director Administrativo, Administrativo

### **10. 📄 Gestión de Informes**
- **Descripción:** Administración de documentos y archivos
- **Funcionalidades:**
  - Upload de documentos
  - Categorización por tipo
  - Control de acceso
  - Descarga segura
- **Usuarios:** Administrador, Director Administrativo

### **11. 📢 Sistema de Novedades**
- **Descripción:** Gestión de contenido informativo
- **Funcionalidades:**
  - CRUD de novedades
  - Publicación en sitio público
  - Segmentación por audiencia
  - Control de visibilidad
- **Usuarios:** Administrador, Director Administrativo, Asistente de Postgrado

### **12. 🆘 Gestión de Emergencias**
- **Descripción:** Sistema de gestión de emergencias
- **Funcionalidades:**
  - Registro de emergencias
  - Clasificación por severidad
  - Notificaciones automáticas
  - Seguimiento de resolución
- **Usuarios:** Todos los roles autenticados

### **13. 📈 Sistema de Analytics**
- **Descripción:** Métricas y estadísticas del sistema
- **Funcionalidades:**
  - Dashboard de métricas
  - Tiempo de respuesta de incidencias
  - Estadísticas de uso
  - Reportes de performance
- **Usuarios:** Administrador, Director Administrativo

### **14. 🔍 Búsqueda Global**
- **Descripción:** Sistema de búsqueda unificada
- **Funcionalidades:**
  - Búsqueda en todos los módulos
  - Filtros avanzados
  - Resultados categorizados
  - Acceso rápido a elementos
- **Usuarios:** Todos los roles autenticados

---

## 🌐 Sitio Público

### **Páginas Públicas Disponibles**
1. **Dashboard Público** (`/`) - Página principal
2. **Calendario Académico** (`/Calendario-Academico`) - Eventos públicos
3. **Equipo FEN** (`/Equipo-FEN`) - Personal de la facultad
4. **Salas FEN** (`/Salas-FEN`) - Espacios disponibles
5. **Módulos FEN** (`/Módulos-FEN`) - Cursos por programa
6. **Archivos FEN** (`/Archivos-FEN`) - Documentos públicos
7. **Novedades** - Contenido informativo

### **Características del Sitio Público**
- **Sin autenticación requerida**
- **Diseño responsive**
- **Optimizado para SEO**
- **Integración con API pública**

---

## 🔌 API Pública

### **Endpoints Principales**
- **Autenticación:** `/api/login`, `/api/register`
- **Datos Públicos:** `/api/public/*` (sin autenticación)
- **Datos Privados:** `/api/*` (con autenticación)
- **Búsqueda:** `/api/search`

### **Características de la API**
- **RESTful Design**
- **Autenticación con Sanctum**
- **Respuestas JSON estandarizadas**
- **Filtros avanzados**
- **Paginación automática**

---

## 🧪 Sistema de Testing

### **Cobertura de Tests**
- **Feature Tests:** 21 archivos de pruebas de integración
- **Unit Tests:** 2 archivos de pruebas unitarias
- **API Tests:** 7 archivos de pruebas de API
- **Auth Tests:** 6 archivos de pruebas de autenticación

### **Módulos Testeados**
- ✅ Usuarios y autenticación
- ✅ Programas de magíster
- ✅ Cursos y clases
- ✅ Salas y recursos
- ✅ Incidencias y emergencias
- ✅ Reportes y analytics
- ✅ API pública y privada

---

## 🎨 Principios HCI Implementados

### **Leyes Aplicadas**
- **Ley de Hick-Hyman:** Menús simplificados (máximo 5 opciones)
- **Ley de Fitts:** Botones grandes y áreas clickables amplias
- **Ley de Miller:** Agrupación en chunks de 3-4 campos
- **Ley de Prägnanz:** Diseño limpio y minimalista
- **Ley de Jakob:** Patrones familiares de formularios web

### **Componentes HCI**
- **Botones:** `hci-button`, `hci-lift`, `hci-focus-ring`
- **Formularios:** `hci-form-group`, `hci-field`
- **Navegación:** `hci-breadcrumb`, `hci-progress-sidebar`
- **Feedback:** `hci-notification-system`, `hci-loading`

---

## 📊 Métricas y Analytics

### **Métricas Implementadas**
1. **Tiempo Promedio de Respuesta a Incidencias**
2. **Estadísticas de Uso por Módulo**
3. **Performance del Sistema**
4. **Métricas de Usuario Activo**

### **Dashboard de Analytics**
- **Visualización de métricas clave**
- **Gráficos interactivos**
- **Exportación de datos**
- **Filtros por período**

---

## 🚀 Instalación y Configuración

### **Requisitos del Sistema**
- PHP 8.1 o superior
- Composer
- Node.js y NPM
- SQLite (desarrollo) o MySQL (producción)

### **Pasos de Instalación**
1. **Clonar repositorio**
2. **Instalar dependencias:** `composer install && npm install`
3. **Configurar entorno:** `.env`
4. **Ejecutar migraciones:** `php artisan migrate`
5. **Compilar assets:** `npm run build`
6. **Iniciar servidor:** `php artisan serve`

---

## 🔧 Mantenimiento y Soporte

### **Logs del Sistema**
- **Laravel Logs:** `storage/logs/laravel.log`
- **Error Tracking:** Configurado para producción
- **Performance Monitoring:** Integrado

### **Backup y Recuperación**
- **Base de Datos:** Scripts automáticos
- **Archivos:** Sistema de respaldo
- **Configuración:** Versionado en Git

---

## 📈 Roadmap y Futuras Mejoras

### **Próximas Funcionalidades**
- **Notificaciones Push**
- **Integración con Sistemas Externos**
- **Dashboard Avanzado de Analytics**
- **Sistema de Reportes Automatizados**

### **Optimizaciones Planificadas**
- **Cache Avanzado**
- **CDN para Assets**
- **Optimización de Consultas**
- **PWA (Progressive Web App)**

---

## 📞 Soporte y Contacto

### **Documentación Técnica**
- **API:** [`API_PUBLICA_COMPLETA.md`](./API_PUBLICA_COMPLETA.md)
- **Testing:** [`GUIA_TESTING_API.md`](./GUIA_TESTING_API.md)
- **Analytics:** [`SISTEMA_ANALYTICS.md`](./SISTEMA_ANALYTICS.md)

### **Desarrollo**
- **Framework:** Laravel 10
- **Frontend:** Blade + Alpine.js + Tailwind CSS
- **Testing:** Pest PHP
- **API:** RESTful con Sanctum

---

**Estado:** ✅ **PRODUCCIÓN**  
**Última Actualización:** Octubre 2025  
**Versión:** 1.0.0
