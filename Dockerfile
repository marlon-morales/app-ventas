# 1. Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# 2. Instalamos las librerías del sistema necesarias para PostgreSQL y utilitarios
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql

# 3. Habilitamos el módulo de reescritura de Apache para las rutas de Laravel
RUN a2enmod rewrite

# 4. Ajustamos la configuración de Apache para que apunte a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Instalamos Composer (el gestor de paquetes de PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 6. Copiamos los archivos de tu proyecto al servidor
COPY . /var/www/html

# 7. Entramos a la carpeta y descargamos las librerías de Laravel
RUN composer install --no-dev --optimize-autoloader

# 8. Asignamos permisos de escritura a las carpetas que Laravel necesita
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Exponemos el puerto 80 (el estándar de la web)
EXPOSE 80

# 10. Comando para iniciar el servidor
CMD ["apache2-foreground"]