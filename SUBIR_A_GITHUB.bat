@echo off
echo ==========================================
echo    CONFIGURACION AUTOMATICA DE GITHUB
echo ==========================================
echo.
echo Este script te ayudara a subir tu proyecto a GitHub.
echo Asegurate de haber creado un repositorio vacio en https://github.com/new
echo.

:: Verificar si git esta instalado
git --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Git no parece estar instalado o no esta en el PATH.
    echo Por favor, reinicia tu computadora si acabas de instalarlo.
    echo O instalalo desde: https://git-scm.com/download/win
    pause
    exit /b
)

echo [1/5] Inicializando repositorio...
git init
git branch -M main

echo [2/5] Agregando archivos...
git add .

echo [3/5] Guardando cambios (Commit)...
git commit -m "Subida inicial del proyecto CMovil"

echo.
echo -------------------------------------------------------
echo Pega la URL de tu repositorio de GitHub (ej. https://github.com/usuario/repo.git):
set /p REPO_URL="URL: "

echo [4/5] Vinculando repositorio remoto...
git remote add origin %REPO_URL%

echo [5/5] Subiendo archivos a GitHub...
git push -u origin main

echo.
echo ==========================================
echo             PROCESO FINALIZADO
echo ==========================================
echo Si hubo algun error de autenticacion, sigue las instrucciones en pantalla para iniciar sesion.
pause
