@echo off
echo ========================================
echo   FEN Platform - Test Suite
echo ========================================
echo.

echo Running all tests...
echo.

php artisan test --parallel

echo.
echo ========================================
echo   Test Summary
echo ========================================
echo.
echo Tests completed!
echo.
echo To run specific test suites:
echo   - php artisan test --testsuite=Feature
echo   - php artisan test --testsuite=Unit
echo   - php artisan test tests/Feature/UsuariosTest.php
echo.
pause

