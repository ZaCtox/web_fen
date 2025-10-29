# 🧪 Guía de Testing Completa - Web FEN

## 📋 Resumen

Esta guía proporciona instrucciones completas para ejecutar todos los tests del sistema Web FEN, incluyendo tests de API, funcionalidades y integración.

---

## 🚀 Configuración Inicial

### **1. Preparar el Entorno**
```bash
# Asegúrate de que el servidor esté corriendo
php artisan serve

# En otra terminal, ejecuta los tests
php artisan test
```

### **2. Configuración de Base de Datos para Tests**
Los tests utilizan una base de datos SQLite en memoria (`:memory:`) que se crea y destruye automáticamente para cada test.

---

## 📊 Cobertura de Tests

### **✅ Feature Tests (Pruebas de Integración)**

#### **1. UsuariosTest.php**
- ✅ Autenticación y autorización
- ✅ CRUD completo de usuarios
- ✅ Validación de campos requeridos
- ✅ Prevención de duplicados de email
- ✅ Asignación de roles

#### **2. PeriodsTest.php**
- ✅ CRUD de períodos académicos
- ✅ Validación de fechas (fecha_fin > fecha_inicio)
- ✅ Filtros por estado (activo/inactivo)
- ✅ Validación de campos requeridos

#### **3. IncidenciasTest.php**
- ✅ Creación y gestión de incidencias
- ✅ Upload de evidencias (archivos)
- ✅ Filtros por estado y prioridad
- ✅ Relaciones con usuarios y salas

#### **4. RoomsTest.php**
- ✅ CRUD de salas
- ✅ Validación de códigos únicos
- ✅ Filtros por tipo y estado
- ✅ Validación de capacidad

#### **5. PlatformIntegrationTest.php**
- ✅ Workflows completos de usuario
- ✅ Accesibilidad de todos los módulos
- ✅ Verificación de wizards
- ✅ Autenticación requerida
- ✅ Búsqueda y filtros

### **✅ API Tests**

#### **1. ClaseApiTest.php**
- ✅ Endpoints de clases públicas
- ✅ Filtros por magíster y período
- ✅ Validación de respuestas JSON

#### **2. CourseApiTest.php**
- ✅ Endpoints de cursos públicos
- ✅ Filtros por año de ingreso
- ✅ Relaciones con magíster

#### **3. EventApiTest.php**
- ✅ Endpoints de eventos públicos
- ✅ Filtros por fecha y tipo
- ✅ Validación de datos

#### **4. InformeApiTest.php**
- ✅ Endpoints de informes públicos
- ✅ Descarga de archivos
- ✅ Filtros por tipo

#### **5. MagisterApiTest.php**
- ✅ Endpoints de magíster públicos
- ✅ Conteo de cursos
- ✅ Validación de respuestas

#### **6. NovedadApiTest.php**
- ✅ Endpoints de novedades públicas
- ✅ Filtros por estado
- ✅ Paginación

#### **7. RoomApiTest.php**
- ✅ Endpoints de salas públicas
- ✅ Filtros por tipo y capacidad
- ✅ Disponibilidad

### **✅ Auth Tests**

#### **1. AuthenticationTest.php**
- ✅ Login y logout
- ✅ Registro de usuarios
- ✅ Verificación de email
- ✅ Recuperación de contraseña

#### **2. EmailVerificationTest.php**
- ✅ Verificación de email
- ✅ Reenvío de verificación
- ✅ Protección de rutas

#### **3. PasswordConfirmationTest.php**
- ✅ Confirmación de contraseña
- ✅ Protección de rutas sensibles

#### **4. PasswordResetTest.php**
- ✅ Solicitud de reset
- ✅ Validación de tokens
- ✅ Actualización de contraseña

#### **5. PasswordUpdateTest.php**
- ✅ Actualización de contraseña
- ✅ Validación de contraseña actual

#### **6. RegistrationTest.php**
- ✅ Registro de nuevos usuarios
- ✅ Validación de campos
- ✅ Asignación de roles

---

## 🎯 Ejecución de Tests

### **Opción 1: Todos los Tests**
```bash
php artisan test
```

### **Opción 2: Tests Específicos**
```bash
# Solo tests de API
php artisan test --filter=Api

# Solo tests de autenticación
php artisan test --filter=Auth

# Solo tests de usuarios
php artisan test --filter=UsuariosTest

# Solo tests de incidencias
php artisan test --filter=IncidenciasTest
```

### **Opción 3: Con Cobertura**
```bash
php artisan test --coverage
```

### **Opción 4: Tests en Paralelo**
```bash
php artisan test --parallel
```

---

## 🔧 Scripts de Testing Automatizados

### **Windows (.bat)**
```bash
# Ejecutar todos los tests
run-tests.bat

# Tests de API específicos
test-api-filtros.bat

# Tests de analytics
test-analytics.bat

# Tests de endpoints públicos
test-publicos.bat

# Tests de login
test-login-simple.bat
```

### **PowerShell (.ps1)**
```powershell
# Tests de login con PowerShell
.\test-login.ps1

# Tests de API simple
.\test-api-simple.ps1
```

---

## 📋 Checklist de Testing

### **✅ Tests de Funcionalidad**
- [ ] Login y autenticación
- [ ] CRUD de usuarios
- [ ] CRUD de magíster
- [ ] CRUD de cursos
- [ ] CRUD de clases
- [ ] CRUD de salas
- [ ] CRUD de incidencias
- [ ] CRUD de reportes diarios
- [ ] CRUD de emergencias
- [ ] CRUD de informes
- [ ] CRUD de novedades

### **✅ Tests de API**
- [ ] Endpoints públicos
- [ ] Endpoints autenticados
- [ ] Filtros y búsquedas
- [ ] Validación de respuestas
- [ ] Manejo de errores
- [ ] Paginación

### **✅ Tests de Integración**
- [ ] Workflows completos
- [ ] Navegación entre módulos
- [ ] Permisos y roles
- [ ] Búsqueda global
- [ ] Dashboard y analytics

---

## 🐛 Troubleshooting

### **Problemas Comunes**

#### **1. Error de Base de Datos**
```bash
# Solución: Limpiar cache y recrear base de datos
php artisan config:clear
php artisan cache:clear
php artisan migrate:fresh
```

#### **2. Error de Permisos**
```bash
# Solución: Verificar permisos de archivos
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

#### **3. Error de Memoria**
```bash
# Solución: Aumentar límite de memoria
php -d memory_limit=512M artisan test
```

#### **4. Tests Lentos**
```bash
# Solución: Ejecutar en paralelo
php artisan test --parallel
```

---

## 📊 Métricas de Testing

### **Cobertura Actual**
- **Feature Tests:** 21 archivos
- **Unit Tests:** 2 archivos
- **API Tests:** 7 archivos
- **Auth Tests:** 6 archivos
- **Total:** 36 archivos de tests

### **Tiempo de Ejecución**
- **Tests Completos:** ~2-3 minutos
- **Tests de API:** ~30 segundos
- **Tests de Auth:** ~20 segundos
- **Tests Paralelos:** ~1-2 minutos

---

## 🎯 Mejores Prácticas

### **1. Ejecutar Tests Antes de Commits**
```bash
# Ejecutar tests antes de hacer commit
git add .
php artisan test
git commit -m "feat: nueva funcionalidad"
```

### **2. Tests Específicos para Desarrollo**
```bash
# Solo tests relacionados con tu cambio
php artisan test --filter=UsuariosTest
```

### **3. Verificar Cobertura**
```bash
# Verificar cobertura de código
php artisan test --coverage
```

---

## 📞 Soporte

### **Documentación Relacionada**
- [`COMO_TESTEAR_LA_API.md`](./COMO_TESTEAR_LA_API.md) - Guía paso a paso
- [`TESTING_MANUAL_RAPIDO.md`](./TESTING_MANUAL_RAPIDO.md) - Testing manual
- [`GUIA_LOGIN_API.md`](./GUIA_LOGIN_API.md) - Testing de autenticación

### **Comandos Útiles**
```bash
# Ver todos los tests disponibles
php artisan test --list-tests

# Ejecutar tests con verbose
php artisan test --verbose

# Ejecutar tests con stop en primer fallo
php artisan test --stop-on-failure
```

---

**Estado:** ✅ **ACTUALIZADO**  
**Última Actualización:** Octubre 2025  
**Cobertura:** 36 archivos de tests
