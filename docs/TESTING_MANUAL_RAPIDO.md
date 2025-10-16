# 🧪 GUÍA DE TESTING MANUAL RÁPIDO

## Cómo probar TODOS los filtros nuevos

---

## 🚀 **OPCIÓN 1: Desde el Navegador**

Simplemente abre estas URLs en tu navegador (con el servidor corriendo):

### ✅ **Cursos Públicos**

```
http://localhost:8000/api/public/courses
http://localhost:8000/api/public/courses?anio_ingreso=2024
http://localhost:8000/api/public/courses/years
http://localhost:8000/api/public/courses/magister/1?anio_ingreso=2024
```

### ✅ **Novedades Públicas**

```
http://localhost:8000/api/public/novedades
http://localhost:8000/api/public/novedades?search=evento
http://localhost:8000/api/public/novedades?tipo=academica
http://localhost:8000/api/public/novedades?magister_id=1
```

### ✅ **Informes Públicos**

```
http://localhost:8000/api/public/informes
http://localhost:8000/api/public/informes?search=calendario
http://localhost:8000/api/public/informes?tipo=academico
http://localhost:8000/api/public/informes?magister_id=1
```

### ✅ **Salas Públicas**

```
http://localhost:8000/api/public/rooms
```

### ✅ **Staff Público**

```
http://localhost:8000/api/public/staff
```

### ✅ **Magísteres Públicos**

```
http://localhost:8000/api/public/magisters
http://localhost:8000/api/public/magisters-with-course-count
```

---

## 🔐 **OPCIÓN 2: Endpoints Autenticados (Necesitas Token)**

### Paso 1: Obtener Token

1. Abre Postman o usa curl:

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"tu@email.com\",\"password\":\"tupassword\"}"
```

2. Copia el `token` de la respuesta

### Paso 2: Usar el Token

Agrega el header en todas las peticiones:
```
Authorization: Bearer TU_TOKEN_AQUI
```

### ✅ **Clases con Filtros**

```bash
# Con token
curl -H "Authorization: Bearer TU_TOKEN" \
  "http://localhost:8000/api/clases?anio_ingreso=2024&trimestre=1"
```

### ✅ **Salas con Filtros**

```bash
curl -H "Authorization: Bearer TU_TOKEN" \
  "http://localhost:8000/api/rooms?search=A301&sort=capacity&direction=desc"
```

### ✅ **Magísteres con Filtros**

```bash
curl -H "Authorization: Bearer TU_TOKEN" \
  "http://localhost:8000/api/magisters?anio_ingreso=2024&sort=nombre"
```

### ✅ **Períodos con Filtros**

```bash
curl -H "Authorization: Bearer TU_TOKEN" \
  "http://localhost:8000/api/periods?magister_id=1&anio_ingreso=2024"
```

### ✅ **Incidencias con Filtros**

```bash
curl -H "Authorization: Bearer TU_TOKEN" \
  "http://localhost:8000/api/incidents?trimestre=1&anio_ingreso=2024"
```

---

## 🎯 **OPCIÓN 3: Script de PowerShell (Windows)**

Crea un archivo `test-api.ps1`:

```powershell
# Variables
$baseUrl = "http://localhost:8000/api"
$publicUrl = "$baseUrl/public"

Write-Host "===== TESTING API PÚBLICA =====" -ForegroundColor Green
Write-Host ""

# Test 1: Cursos
Write-Host "[1] Cursos sin filtro:" -ForegroundColor Cyan
Invoke-RestMethod -Uri "$publicUrl/courses" -Method Get | ConvertTo-Json -Depth 3
Write-Host ""

Write-Host "[2] Cursos con anio_ingreso=2024:" -ForegroundColor Cyan
Invoke-RestMethod -Uri "$publicUrl/courses?anio_ingreso=2024" -Method Get | ConvertTo-Json -Depth 3
Write-Host ""

# Test 2: Años disponibles
Write-Host "[3] Años de ingreso disponibles:" -ForegroundColor Cyan
Invoke-RestMethod -Uri "$publicUrl/courses/years" -Method Get | ConvertTo-Json
Write-Host ""

# Test 3: Novedades
Write-Host "[4] Novedades activas:" -ForegroundColor Cyan
Invoke-RestMethod -Uri "$publicUrl/novedades" -Method Get | ConvertTo-Json -Depth 2
Write-Host ""

Write-Host "[5] Novedades con búsqueda:" -ForegroundColor Cyan
Invoke-RestMethod -Uri "$publicUrl/novedades?search=evento" -Method Get | ConvertTo-Json -Depth 2
Write-Host ""

# Test 4: Informes
Write-Host "[6] Informes:" -ForegroundColor Cyan
Invoke-RestMethod -Uri "$publicUrl/informes" -Method Get | ConvertTo-Json -Depth 2
Write-Host ""

Write-Host "[7] Informes con búsqueda:" -ForegroundColor Cyan
Invoke-RestMethod -Uri "$publicUrl/informes?search=calendario" -Method Get | ConvertTo-Json -Depth 2
Write-Host ""

# Test 5: Salas
Write-Host "[8] Salas:" -ForegroundColor Cyan
Invoke-RestMethod -Uri "$publicUrl/rooms" -Method Get | ConvertTo-Json -Depth 2
Write-Host ""

# Test 6: Staff
Write-Host "[9] Staff:" -ForegroundColor Cyan
Invoke-RestMethod -Uri "$publicUrl/staff" -Method Get | ConvertTo-Json -Depth 2
Write-Host ""

# Test 7: Magísteres
Write-Host "[10] Magísteres con cursos:" -ForegroundColor Cyan
Invoke-RestMethod -Uri "$publicUrl/magisters-with-course-count" -Method Get | ConvertTo-Json -Depth 2
Write-Host ""

Write-Host "===== TESTS COMPLETADOS =====" -ForegroundColor Green
```

**Ejecutar:**
```powershell
.\test-api.ps1
```

---

## 🔧 **OPCIÓN 4: Desde PHP Artisan Tinker**

```bash
php artisan tinker
```

Luego ejecuta:

```php
// Test 1: Cursos con filtro
$response = Http::get('http://localhost:8000/api/public/courses?anio_ingreso=2024');
dd($response->json());

// Test 2: Años disponibles
$response = Http::get('http://localhost:8000/api/public/courses/years');
dd($response->json());

// Test 3: Novedades con búsqueda
$response = Http::get('http://localhost:8000/api/public/novedades?search=evento');
dd($response->json());

// Test 4: Informes con filtros
$response = Http::get('http://localhost:8000/api/public/informes?search=calendario&tipo=academico');
dd($response->json());
```

---

## 🧪 **OPCIÓN 5: Tests Automáticos (PHPUnit/Pest)**

He creado 6 archivos de tests completos:

```bash
# Ejecutar todos los tests de API
php artisan test tests/Feature/Api/

# Ejecutar test específico
php artisan test tests/Feature/Api/CourseApiTest.php
php artisan test tests/Feature/Api/NovedadApiTest.php
php artisan test tests/Feature/Api/InformeApiTest.php
php artisan test tests/Feature/Api/ClaseApiTest.php
php artisan test tests/Feature/Api/RoomApiTest.php
php artisan test tests/Feature/Api/MagisterApiTest.php
php artisan test tests/Feature/Api/EventApiTest.php

# O usar el script que creé:
test-api-filtros.bat
```

---

## 📋 **CHECKLIST DE PRUEBAS**

### Cursos ✅
- [ ] GET `/api/public/courses` retorna cursos
- [ ] GET `/api/public/courses?anio_ingreso=2024` filtra correctamente
- [ ] GET `/api/public/courses/years` retorna años disponibles
- [ ] GET `/api/public/courses/magister/1?anio_ingreso=2024` filtra por magíster y año

### Novedades ✅
- [ ] GET `/api/public/novedades` retorna novedades activas
- [ ] GET `/api/public/novedades?search=evento` busca por texto
- [ ] GET `/api/public/novedades?tipo=academica` filtra por tipo
- [ ] GET `/api/public/novedades?magister_id=1` filtra por magíster
- [ ] GET `/api/novedades?estado=activa` (autenticado) filtra por estado
- [ ] GET `/api/novedades?visibilidad=publica` (autenticado) filtra por visibilidad

### Informes ✅
- [ ] GET `/api/public/informes` retorna informes
- [ ] GET `/api/public/informes?search=calendario` busca por texto
- [ ] GET `/api/public/informes?tipo=academico` filtra por tipo
- [ ] GET `/api/public/informes?magister_id=1` filtra por magíster
- [ ] GET `/api/public/informes?user_id=1` filtra por usuario

### Clases ✅
- [ ] GET `/api/clases` (autenticado) retorna clases paginadas
- [ ] GET `/api/clases?anio_ingreso=2024` filtra por año de ingreso
- [ ] GET `/api/clases?anio=1` filtra por año del programa
- [ ] GET `/api/clases?trimestre=1` filtra por trimestre
- [ ] GET `/api/clases?magister=Gestión` filtra por magíster
- [ ] GET `/api/clases?room_id=5` filtra por sala
- [ ] GET `/api/clases?dia=Viernes` filtra por día
- [ ] GET `/api/clases?estado=activa` filtra por estado

### Salas ✅
- [ ] GET `/api/rooms` (autenticado) retorna salas
- [ ] GET `/api/rooms?search=A301` busca por nombre
- [ ] GET `/api/rooms?ubicacion=Principal` filtra por ubicación
- [ ] GET `/api/rooms?capacidad=40` filtra por capacidad mínima
- [ ] GET `/api/rooms?sort=capacity&direction=desc` ordena por capacidad

### Magísteres ✅
- [ ] GET `/api/magisters` (autenticado) retorna magísteres
- [ ] GET `/api/magisters?anio_ingreso=2024` filtra contador de cursos
- [ ] GET `/api/magisters?q=Gestión` busca por nombre
- [ ] GET `/api/magisters?sort=nombre&direction=asc` ordena

### Períodos ✅
- [ ] GET `/api/periods` (autenticado) retorna períodos
- [ ] GET `/api/periods?magister_id=1` filtra por magíster
- [ ] GET `/api/periods?anio_ingreso=2024` filtra por año de ingreso

### Eventos ✅
- [ ] GET `/api/public/events?start=2024-01-01&end=2024-12-31` retorna eventos
- [ ] GET `/api/public/events?anio_ingreso=2024&start=...&end=...` filtra por año

### Incidencias ✅
- [ ] GET `/api/incidents` (autenticado) retorna incidencias
- [ ] GET `/api/incidents?trimestre=1&anio_ingreso=2024` filtra correctamente

---

## 🎯 **PRUEBA RÁPIDA - 2 MINUTOS**

### 1. Asegúrate que el servidor esté corriendo:
```bash
php artisan serve
```

### 2. Abre estas URLs en tu navegador:

**Test Básico:**
```
http://localhost:8000/api/public/courses/years
```
**Resultado esperado:** JSON con años [2024, 2023, 2022, ...]

**Test con Filtro:**
```
http://localhost:8000/api/public/courses?anio_ingreso=2024
```
**Resultado esperado:** JSON con cursos solo del 2024

**Test de Búsqueda:**
```
http://localhost:8000/api/public/novedades?search=evento
```
**Resultado esperado:** JSON con novedades que contengan "evento"

---

## ✅ **VERIFICACIÓN RÁPIDA**

Si ves esto, está funcionando:

```json
{
    "status": "success",
    "data": [...],
    "meta": {
        "anio_ingreso_filter": "2024",
        "public_view": true
    }
}
```

Si ves un error, revisa:
1. ✅ Servidor corriendo (`php artisan serve`)
2. ✅ Base de datos con datos de prueba
3. ✅ Ruta correcta (verificar URL)

---

## 🎓 **RESULTADO ESPERADO**

Cada endpoint debe:
- ✅ Retornar código 200
- ✅ Tener formato JSON válido
- ✅ Incluir campo `status: "success"`
- ✅ Incluir los filtros aplicados en `meta`
- ✅ Filtrar correctamente los datos

---

**¡Prueba estos endpoints y dime si funcionan!** 🚀

