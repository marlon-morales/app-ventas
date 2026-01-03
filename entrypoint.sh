#!/bin/bash
# Limpiar caché
php artisan config:clear

echo "Probando conexión a la base de datos..."
# Intentar migración
php artisan migrate --force --seed || echo "La migración falló, reintentando conexión..."

# Si la anterior falló, intentamos una vez más tras un pequeño descanso
sleep 5
php artisan migrate --force --seed

exec apache2-foreground