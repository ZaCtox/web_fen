# Etapa 1: dependencias PHP con Composer
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
# Evitamos scripts de composer en esta etapa (no hay app completa aún)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Etapa 2: PHP-FPM + Nginx
FROM php:8.2-fpm

# Paquetes y extensiones necesarias
RUN apt-get update && apt-get install -y \
    nginx gettext-base git unzip libpq-dev libzip-dev zip \
 && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copiamos el código de la app
COPY . .
# Copiamos vendor desde la etapa anterior
COPY --from=vendor /app/vendor ./vendor

# Nginx usa una plantilla para inyectar $PORT
COPY ./nginx.conf /etc/nginx/conf.d/default.conf.template

# Permisos Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Render inyecta $PORT; exponemos uno por defecto para desarrollo local
ENV PORT=8080
EXPOSE 8080

# Arranque: reemplaza $PORT en nginx y levanta Nginx + PHP-FPM
CMD sh -c "envsubst '\$PORT' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf \
  && service nginx start \
  && php-fpm -F"
