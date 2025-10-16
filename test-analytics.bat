@echo off
echo =========================================
echo   TEST SISTEMA DE ANALYTICS
echo =========================================
echo.

echo [1/4] Verificando migracion...
php artisan migrate:status | findstr "create_page_views_table"
if %errorlevel% equ 0 (
    echo [OK] Migracion ejecutada
) else (
    echo [ERROR] Migracion no encontrada
)
echo.

echo [2/4] Verificando tabla page_views...
php artisan tinker --execute="echo \App\Models\PageView::count() . ' registros en page_views';"
echo.

echo [3/4] Verificando ruta analytics...
php artisan route:list | findstr "analytics"
echo.

echo [4/4] Instrucciones de prueba manual:
echo.
echo Para probar el sistema:
echo 1. Accede a http://localhost/analytics (o tu dominio)
echo 2. Navega por diferentes paginas:
echo    - /Calendario-Academico (publico)
echo    - /calendario (admin)
echo    - /dashboard
echo 3. Vuelve a /analytics para ver las estadisticas
echo.
echo =========================================
echo   PRUEBA COMPLETADA
echo =========================================
pause

