# 1. Cambiamos a PHP 7.4 para que sea compatible con Laravel 5.5
FROM php:7.4-apache

# 2. Instalamos dependencias (ajustadas para PHP 7.4)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql gd zip

# 3. Habilitamos rewrite
RUN a2enmod rewrite

# 4. Ajustamos la configuración de Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Instalamos Composer (Versión 2 compatible)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 6. Copiamos los archivos
COPY . /var/www/html

# 7. Instalamos dependencias ignorando requisitos de plataforma (por el salto de versiones)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# 8. Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Copiamos el script de arranque y le damos permisos de ejecución
COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# Usamos el script como el comando de inicio
CMD ["entrypoint.sh"]