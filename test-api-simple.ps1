# Script de Testing Simple para API
# Ejecutar: .\test-api-simple.ps1

$baseUrl = "http://localhost:8000/api"
$publicUrl = "$baseUrl/public"

Write-Host "========================================" -ForegroundColor Green
Write-Host "  TESTING API - FILTROS NUEVOS" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

function Test-Endpoint {
    param($nombre, $url)
    
    Write-Host "[TEST] $nombre" -ForegroundColor Cyan
    Write-Host "URL: $url" -ForegroundColor Gray
    
    try {
        $response = Invoke-WebRequest -Uri $url -Method Get -UseBasicParsing
        
        if ($response.StatusCode -eq 200) {
            Write-Host "[OK] Status: 200" -ForegroundColor Green
            
            # Mostrar primeros caracteres de la respuesta
            $content = $response.Content
            if ($content.Length -gt 200) {
                $preview = $content.Substring(0, 200) + "..."
            } else {
                $preview = $content
            }
            Write-Host "Respuesta: $preview" -ForegroundColor White
        } else {
            Write-Host "[ERROR] Status: $($response.StatusCode)" -ForegroundColor Red
        }
    } catch {
        Write-Host "[ERROR] $_" -ForegroundColor Red
    }
    
    Write-Host ""
}

# ===== TESTS PÚBLICOS (Sin autenticación) =====
Write-Host "===== ENDPOINTS PÚBLICOS =====" -ForegroundColor Yellow
Write-Host ""

Test-Endpoint "1. Años de ingreso disponibles" "$publicUrl/courses/years"
Test-Endpoint "2. Cursos sin filtro" "$publicUrl/courses"
Test-Endpoint "3. Cursos con anio_ingreso=2024" "$publicUrl/courses?anio_ingreso=2024"
Test-Endpoint "4. Novedades activas" "$publicUrl/novedades"
Test-Endpoint "5. Novedades con búsqueda" "$publicUrl/novedades?search=evento"
Test-Endpoint "6. Informes sin filtro" "$publicUrl/informes"
Test-Endpoint "7. Informes con búsqueda" "$publicUrl/informes?search=calendario"
Test-Endpoint "8. Salas" "$publicUrl/rooms"
Test-Endpoint "9. Staff" "$publicUrl/staff"
Test-Endpoint "10. Magísteres" "$publicUrl/magisters"

Write-Host "========================================" -ForegroundColor Green
Write-Host "  TESTS COMPLETADOS" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Para tests autenticados, necesitas obtener un token primero:" -ForegroundColor Yellow
Write-Host "POST $baseUrl/login" -ForegroundColor Gray
Write-Host '{"email":"tu@email.com","password":"tupassword"}' -ForegroundColor Gray

