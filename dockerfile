# Etapa 1: construir los assets con Node
FROM node:18 as build-assets
WORKDIR /app

# Copiar solo package.json para aprovechar cache
COPY package*.json ./
RUN npm install

# Copiar todo y compilar Vite
COPY . .
RUN npm run build

# Etapa 2: PHP + Apache para Laravel
FROM php:8.2-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    unzip git libicu-dev libzip-dev libonig-dev \
    && docker-php-ext-install intl pdo_mysql zip

# Habilitar mod_rewrite de Apache (Laravel lo necesita)
RUN a2enmod rewrite

# Configurar Apache para Laravel (public como root)
WORKDIR /var/www/html
COPY . .
COPY --from=build-assets /app/public/build ./public/build

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar dependencias PHP sin dev
RUN composer install --no-dev --optimize-autoloader

# Permisos para storage y bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Puerto de Apache
EXPOSE 80

CMD ["apache2-foreground"]
