# 🔐 GUÍA DE LOGIN Y AUTENTICACIÓN API

## Cómo obtener un token y usar endpoints autenticados

---

## 🚀 **MÉTODO 1: Script Automático (MÁS FÁCIL)**

### Paso 1: Asegúrate que el servidor esté corriendo

```bash
php artisan serve
```

### Paso 2: Ejecuta el script

```powershell
.\test-login.ps1
```

**✅ Si funciona verás:**
```
[✓] Login exitoso!

Usuario:
  ID: 1
  Nombre: Arcadio Cerda
  Email: acerda@utalca.cl
  Rol: administrador

Token obtenido:
  1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxx

[✓] Endpoint /api/user funciona!
[✓] Endpoint /api/clases funciona!
[✓] Todos los tests pasaron!
```

---

## 🌐 **MÉTODO 2: Desde el Navegador (Para Empezar)**

### Paso 1: Probar Endpoints Públicos (SIN LOGIN)

Abre en tu navegador:

```
http://localhost:8000/api/public/staff
```

**✅ Ahora debe funcionar** (ya corregí el error de `department`)

**Debes ver:**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "name": "Nombre del staff",
            "role": "Cargo",
            "email": "email@example.com",
            "department": "Cargo"
        }
    ]
}
```

---

### Paso 2: Probar otros endpoints públicos

```
✅ http://localhost:8000/api/public/courses/years
✅ http://localhost:8000/api/public/courses?anio_ingreso=2024
✅ http://localhost:8000/api/public/novedades
✅ http://localhost:8000/api/public/informes
✅ http://localhost:8000/api/public/rooms
✅ http://localhost:8000/api/public/magisters
```

---

## 🔐 **MÉTODO 3: Login Manual con Postman**

### Paso 1: Hacer Login

1. **Abre Postman**

2. **Crea un nuevo request:**
   - Método: `POST`
   - URL: `http://localhost:8000/api/login`

3. **En la pestaña "Body":**
   - Selecciona: `raw`
   - Tipo: `JSON`

4. **Pega este JSON:**
   ```json
   {
       "email": "acerda@utalca.cl",
       "password": "password"
   }
   ```

5. **Click en "Send"**

6. **Copia el token de la respuesta:**
   ```json
   {
       "message": "Login exitoso",
       "user": { ... },
       "token": "1|xxxxxxxxxxxxxxxxxx"  ← COPIA ESTO
   }
   ```

---

### Paso 2: Usar el Token

1. **Crea un nuevo request en Postman:**
   - Método: `GET`
   - URL: `http://localhost:8000/api/clases`

2. **En la pestaña "Authorization":**
   - Type: `Bearer Token`
   - Token: `pega el token que copiaste`

3. **Click en "Send"**

4. **✅ Debe funcionar y retornar las clases**

---

### Paso 3: Probar Filtros

Ahora prueba con filtros:

```
GET http://localhost:8000/api/clases?anio_ingreso=2024&trimestre=1
```

(Con el mismo token en Authorization)

---

## 💻 **MÉTODO 4: Desde PowerShell con Curl**

### Paso 1: Obtener Token

```powershell
# Login
$body = @{
    email = "acerda@utalca.cl"
    password = "password"
} | ConvertTo-Json

$loginResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/login" -Method Post -Body $body -ContentType "application/json"

# Guardar token
$token = $loginResponse.token
Write-Host "Token: $token"
```

---

### Paso 2: Usar el Token

```powershell
# Configurar headers con el token
$headers = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}

# Hacer peticiones autenticadas
$clases = Invoke-RestMethod -Uri "http://localhost:8000/api/clases?per_page=10" -Headers $headers
$clases | ConvertTo-Json -Depth 3
```

---

### Paso 3: Probar Filtros

```powershell
# Clases con filtros
$clasesConFiltro = Invoke-RestMethod -Uri "http://localhost:8000/api/clases?anio_ingreso=2024&trimestre=1" -Headers $headers
$clasesConFiltro | ConvertTo-Json -Depth 3

# Salas con búsqueda
$salas = Invoke-RestMethod -Uri "http://localhost:8000/api/rooms?search=A301" -Headers $headers
$salas | ConvertTo-Json -Depth 3

# Magísteres con año
$magisters = Invoke-RestMethod -Uri "http://localhost:8000/api/magisters?anio_ingreso=2024" -Headers $headers
$magisters | ConvertTo-Json -Depth 3
```

---

## 🐛 **SOLUCIÓN DE PROBLEMAS**

### Error: "Credenciales inválidas"

**Solución 1:** Verifica la contraseña

```bash
# Cambiar contraseña del usuario
php artisan tinker
```

Luego en tinker:
```php
$user = App\Models\User::where('email', 'acerda@utalca.cl')->first();
$user->password = Hash::make('password');
$user->save();
echo "Contraseña actualizada";
exit
```

**Solución 2:** Crear un nuevo usuario

```bash
php artisan tinker
```

```php
$user = App\Models\User::create([
    'name' => 'Test User',
    'email' => 'test@test.com',
    'password' => Hash::make('password123'),
    'rol' => 'administrador'
]);
echo "Usuario creado: test@test.com / password123";
exit
```

---

### Error: "Route not found"

```bash
# Limpiar cache de rutas
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

---

### Error: "Unauthenticated" (401)

- ✅ Verifica que estés enviando el header `Authorization: Bearer TOKEN`
- ✅ Verifica que el token no haya expirado
- ✅ Obtén un nuevo token haciendo login otra vez

---

## ✅ **VERIFICACIÓN RÁPIDA**

### Test 1: Login ✅

```json
POST http://localhost:8000/api/login
Body: {
    "email": "acerda@utalca.cl",
    "password": "password"
}
```

**Debe retornar:**
```json
{
    "message": "Login exitoso",
    "user": {...},
    "token": "1|xxxxxx"
}
```

---

### Test 2: User Autenticado ✅

```json
GET http://localhost:8000/api/user
Headers: {
    "Authorization": "Bearer 1|xxxxxx"
}
```

**Debe retornar:**
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "Arcadio Cerda",
        "email": "acerda@utalca.cl",
        "rol": "administrador"
    }
}
```

---

### Test 3: Endpoint con Filtros ✅

```json
GET http://localhost:8000/api/clases?anio_ingreso=2024
Headers: {
    "Authorization": "Bearer 1|xxxxxx"
}
```

**Debe retornar:**
```json
{
    "success": true,
    "data": {
        "data": [...]
    }
}
```

---

## 📝 **RESUMEN DE ENDPOINTS**

### Sin Autenticación (Públicos):
```
GET /api/public/staff       ✅ AHORA FUNCIONA (corregido)
GET /api/public/courses     ✅
GET /api/public/novedades   ✅
GET /api/public/informes    ✅
GET /api/public/rooms       ✅
GET /api/public/magisters   ✅
GET /api/public/events      ✅
```

### Con Autenticación (Requieren Token):
```
POST /api/login            ✅ Obtener token
GET  /api/user             ✅ Usuario actual
POST /api/logout           ✅ Cerrar sesión
GET  /api/clases           ✅ Con filtros
GET  /api/rooms            ✅ Con filtros
GET  /api/magisters        ✅ Con filtros
GET  /api/periods          ✅ Con filtros
GET  /api/novedades        ✅ Con filtros
GET  /api/incidents        ✅ Con filtros
```

---

## 🎯 **CREDENCIALES DE PRUEBA**

Según tu base de datos:

```
Email: acerda@utalca.cl
Password: password (probablemente)
Rol: administrador
```

Si no funciona, ejecuta:
```bash
php artisan db:seed
```

---

## 🚀 **EJECUTA ESTO AHORA**

1. **Abre PowerShell**

2. **Ejecuta:**
   ```powershell
   .\test-login.ps1
   ```

3. **Debes ver:**
   ```
   [✓] Login exitoso!
   [✓] Endpoint /api/user funciona!
   [✓] Endpoint /api/clases funciona!
   ```

4. **Copia el token y úsalo en Postman**

---

**¡Prueba y me dices si ahora funciona!** 🎉

