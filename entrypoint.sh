#!/bin/bash
# Limpiar cualquier caché de configuración vieja
php artisan config:clear
php artisan cache:clear

echo "Intentando migración..."
# Ejecutar migración
php artisan migrate --force --seed

# Iniciar servidor
apache2-foreground