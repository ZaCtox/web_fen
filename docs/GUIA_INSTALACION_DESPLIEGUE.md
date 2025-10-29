# 🚀 Guía de Instalación y Despliegue - Web FEN

## 📋 Resumen

Esta guía proporciona instrucciones completas para instalar, configurar y desplegar el Sistema de Gestión Académica Web FEN en diferentes entornos.

---

## 🔧 Requisitos del Sistema

### **Requisitos Mínimos**
- **PHP:** 8.1 o superior
- **Composer:** 2.0 o superior
- **Node.js:** 16.0 o superior
- **NPM:** 8.0 o superior
- **Base de Datos:** SQLite (desarrollo) / MySQL 8.0+ (producción)
- **Servidor Web:** Apache 2.4+ / Nginx 1.18+

### **Extensiones PHP Requeridas**
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PCRE
- PDO
- Tokenizer
- XML

---

## 🏗️ Instalación Local (Desarrollo)

### **Paso 1: Clonar Repositorio**
```bash
git clone [URL_DEL_REPOSITORIO]
cd Web_FEN
```

### **Paso 2: Instalar Dependencias**
```bash
# Instalar dependencias PHP
composer install

# Instalar dependencias Node.js
npm install
```

### **Paso 3: Configurar Entorno**
```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### **Paso 4: Configurar Base de Datos**
```bash
# Para desarrollo (SQLite)
# El archivo database.sqlite ya existe

# Para producción (MySQL)
# Configurar .env con datos de MySQL
```

### **Paso 5: Ejecutar Migraciones**
```bash
# Ejecutar migraciones
php artisan migrate

# Opcional: Ejecutar seeders
php artisan db:seed
```

### **Paso 6: Compilar Assets**
```bash
# Compilar assets para desarrollo
npm run dev

# O para producción
npm run build
```

### **Paso 7: Iniciar Servidor**
```bash
# Iniciar servidor de desarrollo
php artisan serve
```

**El sistema estará disponible en:** `http://localhost:8000`

---

## 🌐 Instalación en Producción

### **Paso 1: Preparar Servidor**
```bash
# Actualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar PHP 8.1
sudo apt install php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-bcmath

# Instalar Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Instalar Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

### **Paso 2: Configurar Base de Datos**
```bash
# Instalar MySQL
sudo apt install mysql-server

# Crear base de datos
mysql -u root -p
CREATE DATABASE web_fen;
CREATE USER 'web_fen_user'@'localhost' IDENTIFIED BY 'password_seguro';
GRANT ALL PRIVILEGES ON web_fen.* TO 'web_fen_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### **Paso 3: Configurar Aplicación**
```bash
# Configurar .env para producción
APP_NAME="Web FEN"
APP_ENV=production
APP_KEY=base64:TU_CLAVE_AQUI
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_fen
DB_USERNAME=web_fen_user
DB_PASSWORD=password_seguro
```

### **Paso 4: Optimizar para Producción**
```bash
# Optimizar configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Compilar assets para producción
npm run build

# Establecer permisos
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
sudo chmod -R 755 storage/
sudo chmod -R 755 bootstrap/cache/
```

---

## 🔧 Configuración de Servidor Web

### **Apache (.htaccess)**
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### **Nginx**
```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /var/www/web_fen/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 🔐 Configuración de Seguridad

### **1. Configurar HTTPS**
```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-apache

# Obtener certificado SSL
sudo certbot --apache -d tu-dominio.com
```

### **2. Configurar Firewall**
```bash
# Configurar UFW
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

### **3. Configurar Backup Automático**
```bash
#!/bin/bash
# backup.sh
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/web_fen"
DB_NAME="web_fen"

# Crear directorio de backup
mkdir -p $BACKUP_DIR

# Backup de base de datos
mysqldump -u web_fen_user -p$DB_PASSWORD $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# Backup de archivos
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/web_fen/storage/app/public

# Limpiar backups antiguos (más de 30 días)
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
```

---

## 📊 Monitoreo y Logs

### **Configurar Logs**
```bash
# Configurar rotación de logs
sudo nano /etc/logrotate.d/web_fen

# Contenido:
/var/www/web_fen/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 644 www-data www-data
}
```

### **Monitoreo de Performance**
```bash
# Instalar herramientas de monitoreo
sudo apt install htop iotop nethogs

# Configurar monitoreo de Laravel
composer require spatie/laravel-activitylog
```

---

## 🚀 Despliegue Automático

### **Script de Despliegue**
```bash
#!/bin/bash
# deploy.sh

echo "🚀 Iniciando despliegue de Web FEN..."

# Backup actual
echo "📦 Creando backup..."
./backup.sh

# Pull cambios
echo "📥 Descargando cambios..."
git pull origin main

# Instalar dependencias
echo "📦 Instalando dependencias..."
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Ejecutar migraciones
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

# Limpiar cache
echo "🧹 Limpiando cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Establecer permisos
echo "🔐 Estableciendo permisos..."
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/

echo "✅ Despliegue completado!"
```

---

## 🔧 Troubleshooting

### **Problemas Comunes**

#### **1. Error 500 - Internal Server Error**
```bash
# Verificar logs
tail -f storage/logs/laravel.log

# Verificar permisos
sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/
```

#### **2. Error de Base de Datos**
```bash
# Verificar conexión
php artisan tinker
DB::connection()->getPdo();

# Verificar migraciones
php artisan migrate:status
```

#### **3. Error de Assets**
```bash
# Recompilar assets
npm run build

# Verificar permisos de public/
sudo chown -R www-data:www-data public/
```

#### **4. Error de Cache**
```bash
# Limpiar todo el cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 📋 Checklist de Despliegue

### **✅ Pre-Despliegue**
- [ ] Backup de base de datos
- [ ] Backup de archivos
- [ ] Verificar requisitos del servidor
- [ ] Configurar variables de entorno
- [ ] Instalar dependencias

### **✅ Despliegue**
- [ ] Ejecutar migraciones
- [ ] Compilar assets
- [ ] Configurar cache
- [ ] Establecer permisos
- [ ] Configurar servidor web

### **✅ Post-Despliegue**
- [ ] Verificar funcionamiento
- [ ] Probar endpoints críticos
- [ ] Verificar logs
- [ ] Configurar monitoreo
- [ ] Documentar cambios

---

## 📞 Soporte

### **Documentación Relacionada**
- [`PLATAFORMA_WEB_COMPLETA.md`](./PLATAFORMA_WEB_COMPLETA.md) - Documentación completa
- [`API_PUBLICA_COMPLETA.md`](./API_PUBLICA_COMPLETA.md) - Documentación de API
- [`GUIA_TESTING_COMPLETA.md`](./GUIA_TESTING_COMPLETA.md) - Guía de testing

### **Comandos Útiles**
```bash
# Verificar estado de la aplicación
php artisan about

# Verificar configuración
php artisan config:show

# Verificar rutas
php artisan route:list

# Verificar migraciones
php artisan migrate:status
```

---

**Estado:** ✅ **ACTUALIZADO**  
**Última Actualización:** Octubre 2025  
**Versión:** 1.0.0
