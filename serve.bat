@echo off
echo =========================================
echo   La Parrilla - Sistema de Pedidos
echo =========================================
echo.

REM 1. Verificar .env
if not exist .env (
    echo [1/4] Creando archivo .env desde .env.example...
    copy .env.example .env >nul
    echo ✓ Archivo .env creado.
) else (
    echo [1/4] Archivo .env detectado.
)

REM 2. Verificar dependencias PHP
if not exist vendor (
    echo [2/4] Instalando dependencias de PHP (composer)...
    call composer install --no-interaction
    echo ✓ Dependencias de PHP instaladas.
) else (
    echo [2/4] Dependencias PHP (vendor) ok.
)

REM 3. Verificar APP_KEY
findstr /C:"APP_KEY=" .env | findstr /C:"APP_KEY=base64:" >nul
if errorlevel 1 (
    echo [3/4] Generando clave de aplicacion...
    php artisan key:generate --ansi
    echo ✓ Clave generada.
) else (
    echo [3/4] Clave de aplicacion ok.
)

REM 4. Verificar dependencias Node.js
if not exist node_modules (
    echo [4/4] Instalando dependencias de Node.js (npm)...
    call npm install
    echo ✓ Dependencias de Node.js instaladas.
) else (
    echo [4/4] Dependencias Node.js ok.
)

echo.
echo =========================================
echo   Iniciando Servidores
echo =========================================
echo   Laravel: http://127.0.0.1:8000
echo   Vite:    http://127.0.0.1:5173
echo =========================================
echo.
echo Presiona Ctrl+C para detener los servidores.
echo.

start /B php artisan serve
start /B npm run dev

cmd /k
