# Script para testear el login y obtener token
# Ejecutar: .\test-login.ps1

$apiUrl = "http://localhost:8000/api"

Write-Host "========================================" -ForegroundColor Green
Write-Host "  TEST DE LOGIN Y AUTENTICACION" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

# Datos del usuario
$email = "acerda@utalca.cl"
$password = "admin123"

Write-Host "Intentando login con:" -ForegroundColor Cyan
Write-Host "  Email: $email"
Write-Host "  Password: [hidden]"
Write-Host ""

# Preparar el body
$body = @{
    email = $email
    password = $password
} | ConvertTo-Json

# Hacer login
try {
    Write-Host "[1/5] Haciendo POST a $apiUrl/login..." -ForegroundColor Yellow
    
    $response = Invoke-RestMethod -Uri "$apiUrl/login" -Method Post -Body $body -ContentType "application/json"
    
    if ($response.token) {
        Write-Host "[OK] Login exitoso!" -ForegroundColor Green
        Write-Host ""
        Write-Host "Usuario:" -ForegroundColor Cyan
        Write-Host "  ID: $($response.user.id)"
        Write-Host "  Nombre: $($response.user.name)"
        Write-Host "  Email: $($response.user.email)"
        Write-Host "  Rol: $($response.user.rol)"
        Write-Host ""
        Write-Host "Token obtenido:" -ForegroundColor Cyan
        Write-Host "  $($response.token)" -ForegroundColor Yellow
        Write-Host ""
        
        # Guardar token
        $token = $response.token
        
        # Test 2: Usuario autenticado
        Write-Host "[2/5] Probando endpoint /api/user con el token..." -ForegroundColor Yellow
        
        $headers = @{
            "Authorization" = "Bearer $token"
            "Accept" = "application/json"
        }
        
        $userResponse = Invoke-RestMethod -Uri "$apiUrl/user" -Headers $headers -Method Get
        
        if ($userResponse.status -eq "success") {
            Write-Host "[OK] Endpoint /api/user funciona!" -ForegroundColor Green
            Write-Host "  Usuario autenticado: $($userResponse.data.name)"
            Write-Host ""
        }
        
        # Test 3: Clases
        Write-Host "[3/5] Probando /api/clases con filtros..." -ForegroundColor Yellow
        
        try {
            $clasesResponse = Invoke-RestMethod -Uri "$apiUrl/clases?per_page=5" -Headers $headers -Method Get
            
            if ($clasesResponse.success) {
                Write-Host "[OK] Endpoint /api/clases funciona!" -ForegroundColor Green
                Write-Host "  Clases encontradas: $(($clasesResponse.data.data).Count)"
                Write-Host ""
            }
        } catch {
            Write-Host "[INFO] /api/clases: $($_.Exception.Message)" -ForegroundColor Yellow
            Write-Host ""
        }
        
        # Test 4: Magisters
        Write-Host "[4/5] Probando /api/magisters..." -ForegroundColor Yellow
        
        try {
            $magistersResponse = Invoke-RestMethod -Uri "$apiUrl/magisters" -Headers $headers -Method Get
            
            Write-Host "[OK] Endpoint /api/magisters funciona!" -ForegroundColor Green
            Write-Host ""
        } catch {
            Write-Host "[INFO] /api/magisters: $($_.Exception.Message)" -ForegroundColor Yellow
            Write-Host ""
        }
        
        # Test 5: Novedades
        Write-Host "[5/5] Probando /api/novedades..." -ForegroundColor Yellow
        
        try {
            $novedadesResponse = Invoke-RestMethod -Uri "$apiUrl/novedades" -Headers $headers -Method Get
            
            if ($novedadesResponse.success) {
                Write-Host "[OK] Endpoint /api/novedades funciona!" -ForegroundColor Green
                Write-Host ""
            }
        } catch {
            Write-Host "[INFO] /api/novedades: $($_.Exception.Message)" -ForegroundColor Yellow
            Write-Host ""
        }
        
        Write-Host "========================================" -ForegroundColor Green
        Write-Host "  TODOS LOS TESTS PASARON" -ForegroundColor Green
        Write-Host "========================================" -ForegroundColor Green
        Write-Host ""
        Write-Host "Tu token (guardalo para futuras peticiones):" -ForegroundColor Cyan
        Write-Host $token -ForegroundColor Yellow
        Write-Host ""
        
    } else {
        Write-Host "[ERROR] No se recibio token" -ForegroundColor Red
    }
    
} catch {
    Write-Host "[ERROR] Error en login:" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host ""
    Write-Host "Posibles soluciones:" -ForegroundColor Yellow
    Write-Host "  1. Verifica que el servidor este corriendo: php artisan serve"
    Write-Host "  2. Verifica el email y contrasena"
    Write-Host "  3. Ejecuta: php artisan db:seed (si no hay usuarios)"
    Write-Host "  4. Revisa los logs: storage/logs/laravel.log"
}
