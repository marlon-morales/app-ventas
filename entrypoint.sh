#!/bin/bash

# Esperar a que el sistema esté listo
sleep 3

# Forzar la limpieza de caché de configuración para que lea las variables de Render
php artisan config:clear
php artisan cache:clear

echo "Iniciando migración forzada..."
# Ejecutamos la migración
php artisan migrate --force --seed

# Iniciamos Apache
exec apache2-foreground