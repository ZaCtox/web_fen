# Imagen base PHP con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql zip gd mbstring

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# Copiar proyecto
COPY . /var/www/html
WORKDIR /var/www/html

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# 🚨 Eliminar la configuración por defecto y usar la personalizada 🚨
RUN rm /etc/apache2/sites-available/000-default.conf

# 🚨 Copiar el nuevo archivo de configuración 🚨
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ports.conf /etc/apache2/ports.conf

# Permisos para Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer puerto de Render
EXPOSE 80

# Arrancar Apache
CMD ["apache2-foreground"]
