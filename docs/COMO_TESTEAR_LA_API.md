# 🧪 CÓMO TESTEAR LA API - GUÍA PASO A PASO (ACTUALIZADA)

## 📅 Diciembre 2024 - Roles y Endpoints Actualizados

## 📋 Guía Completa para Verificar que TODO Funciona

---

## ⚠️ **CAMBIOS IMPORTANTES**
- ❌ **Rol `administrador` eliminado** - Ya no existe
- ❌ **Rol `visor` eliminado** - Completamente removido
- ✅ **Nuevos roles**: `director_administrativo`, `decano`, `director_programa`, `asistente_programa`, `asistente_postgrado`, `docente`, `técnico`, `auxiliar`
- ✅ **Nuevo endpoint**: `/api/analytics` para estadísticas
- ✅ **Filtros mejorados** en todos los controladores

---

## 🚀 **PASO 1: Iniciar el Servidor** (IMPORTANTE)

```bash
php artisan serve
```

**Debe mostrar:**
```
Starting Laravel development server: http://127.0.0.1:8000
```

Deja esta ventana abierta.

---

## ✅ **PASO 2: Probar Endpoints Públicos (Sin Token)**

Abre tu navegador y prueba estas URLs **UNA POR UNA**:

### Test 1: Años de Ingreso Disponibles
```
http://localhost:8000/api/public/courses/years
```

**✅ Debe mostrar:**
```json
{
    "status": "success",
    "data": [2024, 2023, 2022, 2021]
}
```

---

### Test 2: Cursos SIN Filtro
```
http://localhost:8000/api/public/courses
```

**✅ Debe mostrar:**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "nombre": "Gestión Estratégica",
            "magister_id": 1,
            "period": {
                "anio_ingreso": 2024
            }
        }
    ]
}
```

---

### Test 3: Cursos CON Filtro de Año
```
http://localhost:8000/api/public/courses?anio_ingreso=2024
```

**✅ Debe mostrar:**
- Solo cursos del 2024
- `"anio_ingreso_filter": "2024"` en meta

---

### Test 4: Novedades Activas
```
http://localhost:8000/api/public/novedades
```

**✅ Debe mostrar:**
```json
{
    "success": true,
    "data": [...]
}
```

---

### Test 5: Novedades con Búsqueda
```
http://localhost:8000/api/public/novedades?search=evento
```

**✅ Debe mostrar:**
- Solo novedades que contengan "evento" en título o contenido

---

### Test 6: Informes
```
http://localhost:8000/api/public/informes
```

**✅ Debe mostrar:**
```json
{
    "status": "success",
    "data": {
        "data": [...]
    }
}
```

---

### Test 7: Informes con Búsqueda
```
http://localhost:8000/api/public/informes?search=calendario
```

**✅ Debe mostrar:**
- Solo informes que contengan "calendario"

---

### Test 8: Salas
```
http://localhost:8000/api/public/rooms
```

**✅ Debe mostrar:**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "name": "Sala A301",
            "equipment": "Calefacción, Computador, ..."
        }
    ]
}
```

---

### Test 9: Staff
```
http://localhost:8000/api/public/staff
```

**✅ Debe mostrar:**
- Lista del equipo FEN

---

### Test 10: Magísteres
```
http://localhost:8000/api/public/magisters-with-course-count
```

**✅ Debe mostrar:**
- Magísteres con contador de cursos

---

## 🔐 **PASO 3: Probar Endpoints Autenticados (Con Token)**

### 3.1 Obtener Token

**Opción A: Desde Postman**

1. Crea un POST request a:
   ```
   http://localhost:8000/api/login
   ```

2. En Body (JSON):
   ```json
   {
       "email": "admin@test.com",
       "password": "password"
   }
   ```

3. Copia el `token` de la respuesta

**Opción B: Desde curl (PowerShell)**

```powershell
$body = @{
    email = "admin@test.com"
    password = "password"
} | ConvertTo-Json

$response = Invoke-RestMethod -Uri "http://localhost:8000/api/login" -Method Post -Body $body -ContentType "application/json"
$token = $response.token
Write-Host "Token: $token"
```

---

### 3.2 Usar el Token

**En Postman:**
- Pestaña "Authorization"
- Type: "Bearer Token"
- Pegar tu token

**En curl (PowerShell):**
```powershell
$headers = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}

Invoke-RestMethod -Uri "http://localhost:8000/api/clases" -Headers $headers
```

---

### 3.3 Probar Endpoints Autenticados

#### Test 1: Clases con Filtros
```
GET http://localhost:8000/api/clases?anio_ingreso=2024&trimestre=1
```

#### Test 2: Salas con Búsqueda
```
GET http://localhost:8000/api/rooms?search=A301&sort=capacity&direction=desc
```

#### Test 3: Magísteres con Año
```
GET http://localhost:8000/api/magisters?anio_ingreso=2024
```

#### Test 4: Períodos con Filtros
```
GET http://localhost:8000/api/periods?magister_id=1&anio_ingreso=2024
```

#### Test 5: Novedades con Filtros Completos
```
GET http://localhost:8000/api/novedades?estado=activa&visibilidad=publica
```

---

## 🎯 **PASO 4: Verificación Rápida**

### Checklist de Verificación:

- [ ] ✅ Servidor corriendo (`php artisan serve`)
- [ ] ✅ Base de datos con datos (`php artisan db:seed`)
- [ ] ✅ `/api/public/courses/years` retorna años
- [ ] ✅ `/api/public/courses?anio_ingreso=2024` filtra
- [ ] ✅ `/api/public/novedades?search=evento` busca
- [ ] ✅ `/api/public/informes?search=calendario` busca
- [ ] ✅ Puedes obtener token con `/api/login`
- [ ] ✅ Con token puedes acceder a `/api/clases`
- [ ] ✅ Filtros en clases funcionan

---

## 🐛 **SOLUCIÓN DE PROBLEMAS**

### Error 404 "Route not found"
```bash
# Limpiar cache de rutas
php artisan route:clear
php artisan route:cache
```

### Error 500 "Internal Server Error"
```bash
# Ver logs
php artisan tail

# O revisar:
storage/logs/laravel.log
```

### Error 401 "Unauthenticated"
```bash
# Verificar que el token sea válido
# Obtener nuevo token con /api/login
```

### No hay datos
```bash
# Ejecutar seeders
php artisan db:seed
```

---

## 📊 **RESULTADOS ESPERADOS**

### ✅ Endpoints Públicos (200 OK)
```
✅ /api/public/courses/years
✅ /api/public/courses
✅ /api/public/courses?anio_ingreso=2024
✅ /api/public/novedades
✅ /api/public/novedades?search=texto
✅ /api/public/informes
✅ /api/public/informes?search=texto
✅ /api/public/rooms
✅ /api/public/staff
✅ /api/public/magisters
✅ /api/public/events?start=...&end=...
```

### ✅ Endpoints Autenticados (200 OK con token)
```
✅ /api/clases?anio_ingreso=2024
✅ /api/rooms?search=A301
✅ /api/magisters?anio_ingreso=2024
✅ /api/periods?magister_id=1
✅ /api/novedades?estado=activa
✅ /api/incidents?trimestre=1
```

---

## 🎓 **EJEMPLO COMPLETO DE TEST**

### 1. Iniciar servidor
```bash
php artisan serve
```

### 2. Abrir navegador en:
```
http://localhost:8000/api/public/courses/years
```

### 3. Debes ver algo como:
```json
{
    "status": "success",
    "data": [2024, 2023, 2022]
}
```

### 4. Probar con filtro:
```
http://localhost:8000/api/public/courses?anio_ingreso=2024
```

### 5. Verificar que `meta.anio_ingreso_filter` sea "2024"

---

## 🔐 **PASO 3: Testing de Roles Actualizados**

### **Test 1: Registro con Rol Válido**
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Docente",
    "email": "docente@test.com",
    "password": "password123",
    "password_confirmation": "password123",
    "rol": "docente"
  }'
```

### **Test 2: Registro con Rol Inválido (debería fallar)**
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Admin",
    "email": "admin@test.com",
    "password": "password123",
    "password_confirmation": "password123",
    "rol": "administrador"
  }'
```

### **Test 3: Login y Obtener Token**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "docente@test.com",
    "password": "password123"
  }'
```

### **Test 4: Intentar Crear Staff sin Permisos (debería fallar)**
```bash
curl -X POST http://localhost:8000/api/staff \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Test Staff",
    "email": "staff@test.com",
    "cargo": "Test Cargo"
  }'
```

---

## 📊 **PASO 4: Testing de Analytics (Nuevo Endpoint)**

### **Test 1: Estadísticas Generales**
```bash
curl -X GET http://localhost:8000/api/analytics \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### **Test 2: Estadísticas por Período**
```bash
curl -X GET "http://localhost:8000/api/analytics/period-stats?anio_ingreso=2024&anio=1&trimestre=1" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### **Respuesta Esperada:**
```json
{
  "status": "success",
  "data": {
    "usuarios": { "total": 10, "por_rol": {...} },
    "incidencias": { "total": 5, "por_estado": {...} },
    "cursos": { "total": 20, "por_magister": {...} },
    "clases": { "total": 15, "por_modalidad": {...} },
    "reportes_diarios": { "total": 3, "este_mes": 1 },
    "novedades": { "total": 4, "urgentes": 2 },
    "emergencias": { "total": 1, "activas": 0 },
    "staff": { "total": 12, "por_cargo": {...} }
  }
}
```

---

## 🔍 **PASO 5: Testing de Filtros Mejorados**

### **Cursos con Filtros Combinados**
```bash
curl -X GET "http://localhost:8000/api/courses?search=economia&anio_ingreso=2024&anio=1&trimestre=1" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### **Incidencias con Filtros**
```bash
curl -X GET "http://localhost:8000/api/incidents?estado=pendiente&anio=2024&trimestre=1" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### **Clases con Filtros**
```bash
curl -X GET "http://localhost:8000/api/clases?anio_ingreso=2024&anio=1&room_id=1" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## ✅ **SI TODO FUNCIONA**

Verás:
- ✅ Status 200 para endpoints públicos
- ✅ Status 401/403 para endpoints sin permisos
- ✅ JSON bien formateado
- ✅ Filtros aplicados correctamente
- ✅ Roles validados correctamente
- ✅ Analytics funcionando

---

## 💡 **TIPS ACTUALIZADOS**

1. **Roles válidos para testing:**
   - `director_administrativo` - Máximo acceso
   - `decano` - Solo lectura
   - `docente` - Solo calendario y clases
   - `asistente_postgrado` - Acceso a reportes diarios

2. **Endpoints que requieren permisos específicos:**
   - `/api/staff` - Solo director_administrativo, decano
   - `/api/daily-reports` - Solo asistente_postgrado, decano
   - `/api/analytics` - Solo director_administrativo, decano, director_programa, asistente_postgrado

3. **Usa Postman para testing avanzado:**
   - Guarda tokens automáticamente
   - Prueba diferentes roles fácilmente
   - Ve respuestas completas

---

**¡Prueba estos endpoints actualizados y me dices si funcionan!** 🚀

**Documentación completa:** `docs/API_ACTUALIZACION_COMPLETA.md`

