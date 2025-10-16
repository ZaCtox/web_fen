# 🧪 CÓMO TESTEAR LA API - GUÍA PASO A PASO

## 📋 Guía Completa para Verificar que TODO Funciona

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

## ✅ **SI TODO FUNCIONA**

Verás:
- ✅ Status 200
- ✅ JSON bien formateado
- ✅ Datos correctos
- ✅ Filtros aplicados en meta

---

## 💡 **TIPS**

1. **Usa extensiones de navegador:**
   - JSON Viewer para Chrome
   - JSONView para Firefox

2. **Usa Postman:**
   - Más fácil para tests con token
   - Guarda colecciones de requests

3. **Revisa los logs:**
   ```bash
   php artisan tail
   ```

---

**¡Prueba estos endpoints y me dices si funcionan!** 🚀

