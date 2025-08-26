# Imagen base con PHP, Composer y extensiones comunes
FROM php:8.2-apache

# Instalar dependencias del sistema necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# Configurar Apache para servir desde /public
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

# Copiar archivos de Laravel
WORKDIR /var/www/html
COPY . .

# Instalar dependencias de Laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Generar clave de Laravel (solo si APP_KEY no existe en env)
RUN php artisan key:generate --force || true

# Permisos de storage y cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer puerto
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
