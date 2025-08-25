# Imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar el proyecto
COPY . /var/www/html

# Definir directorio de trabajo
WORKDIR /var/www/html

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Instalar Node y compilar assets
RUN apt-get update && apt-get install -y nodejs npm
RUN npm install && npm run build

# Permisos de storage y bootstrap
RUN chmod -R 777 storage bootstrap/cache

# Exponer puerto
EXPOSE 8080

# Start Apache
CMD ["apache2-foreground"]
