@echo off
echo ========================================
echo  TESTS DE API - FILTROS NUEVOS
echo ========================================
echo.

echo [1/6] Testeando CourseController...
php artisan test --filter=CourseApiTest

echo.
echo [2/6] Testeando NovedadController...
php artisan test --filter=NovedadApiTest

echo.
echo [3/6] Testeando InformeController...
php artisan test --filter=InformeApiTest

echo.
echo [4/6] Testeando ClaseController...
php artisan test --filter=ClaseApiTest

echo.
echo [5/6] Testeando RoomController...
php artisan test --filter=RoomApiTest

echo.
echo [6/6] Testeando MagisterController...
php artisan test --filter=MagisterApiTest

echo.
echo [BONUS] Testeando EventController...
php artisan test --filter=EventApiTest

echo.
echo ========================================
echo  TESTS COMPLETADOS
echo ========================================
pause

