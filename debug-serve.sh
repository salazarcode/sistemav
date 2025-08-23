#!/bin/bash

# Script para iniciar Laravel con Xdebug en WSL
echo "Starting Laravel with Xdebug debugging enabled..."

# Configurar variables de entorno para Xdebug
export XDEBUG_CONFIG="idekey=VSCODE"
export XDEBUG_MODE="debug"
export XDEBUG_SESSION="VSCODE"

# Cambiar al directorio de la aplicación Laravel
cd /mnt/e/repos/sistemav/app

# Iniciar el servidor Laravel con las configuraciones de Xdebug
php -dxdebug.mode=debug \
    -dxdebug.start_with_request=yes \
    -dxdebug.client_host=localhost \
    -dxdebug.client_port=9003 \
    -dxdebug.idekey=VSCODE \
    artisan serve --host=0.0.0.0 --port=8000