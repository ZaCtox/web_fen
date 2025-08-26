# Etapa 1: Composer
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Etapa 2: PHP + Nginx
FROM php:8.2-fpm

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev zip nginx \
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Configurar directorio
WORKDIR /var/www/html

# Copiar archivos de Laravel
COPY . .

# Copiar dependencias
COPY --from=vendor /app/vendor ./vendor

# Configuración Nginx
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# Dar permisos
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponer puerto
EXPOSE 80

# Iniciar Nginx + PHP-FPM
CMD service nginx start && php-fpm
