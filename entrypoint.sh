#!/bin/bash

# Ejecutar migraciones automáticamente
php artisan migrate --force

# Iniciar el servidor Apache (el comando original del Dockerfile)
apache2-foreground