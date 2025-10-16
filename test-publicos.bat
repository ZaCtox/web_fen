@echo off
echo ========================================
echo   TEST DE ENDPOINTS PUBLICOS
echo ========================================
echo.

echo [1/7] Probando Staff...
curl -s http://localhost:8000/api/public/staff
echo.
echo.

echo [2/7] Probando Anos de ingreso...
curl -s http://localhost:8000/api/public/courses/years
echo.
echo.

echo [3/7] Probando Cursos...
curl -s http://localhost:8000/api/public/courses
echo.
echo.

echo [4/7] Probando Cursos con filtro...
curl -s "http://localhost:8000/api/public/courses?anio_ingreso=2024"
echo.
echo.

echo [5/7] Probando Novedades...
curl -s http://localhost:8000/api/public/novedades
echo.
echo.

echo [6/7] Probando Informes...
curl -s http://localhost:8000/api/public/informes
echo.
echo.

echo [7/7] Probando Rooms...
curl -s http://localhost:8000/api/public/rooms
echo.
echo.

echo ========================================
echo   TESTS COMPLETADOS
echo ========================================
pause

