# Imagen base oficial PHP con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip gd mbstring

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Cambiar Apache al puerto 10000 (Render exige este puerto)
RUN echo "Listen 10000" > /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:10000>/' /etc/apache2/sites-enabled/000-default.conf

# Copiar proyecto
COPY . /var/www/html
WORKDIR /var/www/html/public


# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Instalar dependencias PHP del proyecto
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Permisos
RUN chmod -R 777 storage bootstrap/cache

# Exponer puerto de Render
EXPOSE 10000

# Arrancar Apache
CMD ["apache2-foreground"]
