#!/bin/bash
# Esperar unos segundos a que la red esté lista
sleep 5

# Intentar migrar. Si falla, el contenedor se reiniciará y volverá a intentar.
php artisan migrate:fresh --seed --force

# Iniciar Apache
apache2-foreground