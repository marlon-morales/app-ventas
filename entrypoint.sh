#!/bin/bash
# Limpiar caché para asegurar que lea la nueva DATABASE_URL
php artisan config:clear

echo "Verificando conexión a Supabase..."
# Intentar migración
php artisan migrate --force --seed || echo "La migración falló, pero iniciaremos el servidor para debug."

# Iniciar servidor
apache2-foreground