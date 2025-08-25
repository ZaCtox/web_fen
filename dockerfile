# Imagen base oficial PHP con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Cambiar Apache al puerto 10000 (Render exige este puerto)
RUN sed -i 's/80/10000/' /etc/apache2/ports.conf && \
    sed -i 's/:80/:10000/' /etc/apache2/sites-enabled/000-default.conf

# Copiar proyecto
COPY . /var/www/html
WORKDIR /var/www/html

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ✅ OJO: Quitamos instalación de Node/NPM porque Render free se queda corto en RAM
# (sube tu carpeta public/build o public/js ya compilada desde tu máquina local)
# RUN apt-get update && apt-get install -y nodejs npm
# RUN npm install && npm run build

# Permisos
RUN chmod -R 777 storage bootstrap/cache

# Exponer puerto de Render
EXPOSE 10000

# Arrancar Apache
CMD ["apache2-foreground"]
