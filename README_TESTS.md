# 🧪 FEN Platform - Test Suite

## 📋 Resumen de Tests

Esta plataforma cuenta con una suite completa de tests automatizados para garantizar la calidad y estabilidad del código.

### 🎯 Cobertura de Tests

#### ✅ **Feature Tests** (Pruebas de Integración)
- **UsuariosTest.php** - Tests del módulo de usuarios
  - Autenticación y autorización
  - Creación, edición y eliminación de usuarios
  - Validación de campos
  - Prevención de duplicados
  
- **PeriodsTest.php** - Tests del módulo de períodos académicos
  - CRUD completo de períodos
  - Validación de fechas (fecha_fin > fecha_inicio)
  - Filtros por estado (activo/inactivo)
  - Validación de campos requeridos

- **IncidenciasTest.php** - Tests del módulo de incidencias
  - Creación y gestión de incidencias
  - Upload de evidencias (archivos)
  - Filtros por estado y prioridad
  - Relaciones con usuarios y salas

- **RoomsTest.php** - Tests del módulo de salas
  - CRUD de salas
  - Validación de códigos únicos
  - Filtros por tipo y estado
  - Validación de capacidad

- **MallasCurricularesTest.php** - Tests del módulo de mallas curriculares
  - Creación y actualización de mallas
  - Validación de años (año_fin > año_inicio)
  - Relaciones con magisters
  - Estados (activo, borrador)

- **PlatformIntegrationTest.php** - Tests de integración completos
  - Workflows completos de usuario
  - Accesibilidad de todos los módulos
  - Verificación de wizards
  - Autenticación requerida
  - Búsqueda y filtros
  - Integridad de datos

#### ✅ **Unit Tests** (Pruebas Unitarias)
- **ModelsTest.php** - Tests de modelos y relaciones
  - Relaciones entre modelos (belongsTo, hasMany)
  - Campos fillables
  - Castings de tipos (dates, booleans)
  - Validaciones de base de datos

### 🚀 Cómo Ejecutar los Tests

#### Opción 1: Ejecutar todos los tests
```bash
php artisan test
```

#### Opción 2: Ejecutar con el script automático (Windows)
```bash
run-tests.bat
```

#### Opción 3: Ejecutar tests en paralelo (más rápido)
```bash
php artisan test --parallel
```

#### Opción 4: Ejecutar suite específica
```bash
# Solo tests Feature
php artisan test --testsuite=Feature

# Solo tests Unit
php artisan test --testsuite=Unit
```

#### Opción 5: Ejecutar archivo específico
```bash
php artisan test tests/Feature/UsuariosTest.php
php artisan test tests/Feature/IncidenciasTest.php
php artisan test tests/Unit/ModelsTest.php
```

#### Opción 6: Ejecutar con cobertura
```bash
php artisan test --coverage
```

### 📊 Módulos Probados

| Módulo | Feature Tests | Unit Tests | Estado |
|--------|--------------|------------|--------|
| **Usuarios** | ✅ | ✅ | Completo |
| **Períodos** | ✅ | ✅ | Completo |
| **Incidencias** | ✅ | ✅ | Completo |
| **Salas (Rooms)** | ✅ | ✅ | Completo |
| **Mallas Curriculares** | ✅ | ✅ | Completo |
| **Cursos** | ✅ | ✅ | Completo |
| **Clases** | ✅ | ✅ | Completo |
| **Integración** | ✅ | N/A | Completo |

### 🔍 Qué se Prueba

#### 1. **Autenticación y Autorización**
- ✅ Acceso protegido a rutas
- ✅ Redirección a login cuando no autenticado
- ✅ Roles y permisos

#### 2. **CRUD Completo**
- ✅ Creación de registros
- ✅ Lectura y listado
- ✅ Actualización de registros
- ✅ Eliminación de registros

#### 3. **Validaciones**
- ✅ Campos requeridos
- ✅ Formatos (email, fechas)
- ✅ Valores únicos (códigos, emails)
- ✅ Lógica de negocio (fechas, años)

#### 4. **Relaciones entre Modelos**
- ✅ belongsTo (pertenece a)
- ✅ hasMany (tiene muchos)
- ✅ Integridad referencial

#### 5. **Funcionalidades Avanzadas**
- ✅ Upload de archivos
- ✅ Búsqueda y filtros
- ✅ Estados y prioridades
- ✅ Workflows completos

#### 6. **Wizards**
- ✅ Accesibilidad de todos los wizards
- ✅ Navegación entre pasos
- ✅ Validación por pasos

### 📈 Resultados Esperados

Al ejecutar los tests, deberías ver algo como:

```
PASS  Tests\Feature\UsuariosTest
✓ can access usuarios index when authenticated
✓ cannot access usuarios index when not authenticated
✓ can view create usuario form
✓ can create a new usuario
✓ validates required fields when creating usuario
✓ validates email format when creating usuario
...

PASS  Tests\Feature\PlatformIntegrationTest
✓ can complete full user workflow
✓ can complete full room and incident workflow
✓ can complete full academic period workflow
✓ verifies all main modules are accessible
✓ verifies all wizards are accessible
...

Tests:    85 passed
Duration: 2.34s
```

### 🛠️ Configuración

Los tests utilizan:
- **Pest PHP** - Framework de testing moderno
- **RefreshDatabase** - Base de datos limpia para cada test
- **Factories** - Generación de datos de prueba
- **Faker** - Datos aleatorios realistas

### 🎯 Mejores Prácticas

1. **Ejecuta los tests antes de hacer commit**
   ```bash
   php artisan test
   ```

2. **Ejecuta tests específicos cuando trabajas en un módulo**
   ```bash
   php artisan test tests/Feature/UsuariosTest.php
   ```

3. **Verifica la cobertura periódicamente**
   ```bash
   php artisan test --coverage --min=80
   ```

4. **Los tests deben ser rápidos**
   - Usa `--parallel` para ejecutar en paralelo
   - Usa factories en lugar de crear datos manualmente

### 📝 Agregar Nuevos Tests

Para agregar tests a un módulo nuevo:

```php
<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Mi Nuevo Módulo', function () {
    
    beforeEach(function () {
        $this->admin = User::factory()->create([
            'rol' => 'director_administrativo',
        ]);
    });

    it('can do something', function () {
        $response = $this->actingAs($this->admin)->get('/mi-ruta');
        
        $response->assertStatus(200);
    });
});
```

### ✅ Checklist de Testing

Antes de considerar un módulo completo, asegúrate de que tenga:

- [ ] Tests de acceso autenticado
- [ ] Tests de acceso no autenticado
- [ ] Tests de creación (POST)
- [ ] Tests de lectura (GET)
- [ ] Tests de actualización (PUT)
- [ ] Tests de eliminación (DELETE)
- [ ] Tests de validación de campos
- [ ] Tests de relaciones de modelos
- [ ] Tests de filtros y búsqueda
- [ ] Tests de workflows completos

### 🚨 Troubleshooting

Si los tests fallan:

1. **Verifica la base de datos de testing**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Regenera la base de datos**
   ```bash
   php artisan migrate:fresh --env=testing
   ```

3. **Revisa los logs**
   - Los errores detallados se muestran en la consola
   - Usa `--stop-on-failure` para detener en el primer error

### 📞 Soporte

Si encuentras problemas con los tests:
1. Revisa este README
2. Ejecuta `php artisan test --help` para ver opciones
3. Consulta la documentación de Pest PHP: https://pestphp.com

---

**¡Felices Tests! 🎉**

