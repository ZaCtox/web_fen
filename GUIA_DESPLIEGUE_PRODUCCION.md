# 🚀 Guía de Despliegue a Producción - Universidad

## 📋 Checklist de Cambios para Producción

### 🔧 1. Archivo `.env` - Configuración Principal

#### **Base de Datos**
```env
# Cambiar a la BD de producción de la universidad
DB_CONNECTION=mysql
DB_HOST=servidor-bd.universidad.cl
DB_PORT=3306
DB_DATABASE=fen_platform_prod
DB_USERNAME=usuario_bd_universidad
DB_PASSWORD=password_seguro_universidad
```

#### **URLs y Dominio**
```env
# Cambiar al dominio oficial de la universidad
APP_URL=https://fen.universidad.cl
APP_NAME="Plataforma FEN - Universidad"
APP_ENV=production
APP_DEBUG=false
```

#### **Cloudinary (Archivos)**
```env
# Configurar Cloudinary de la universidad
CLOUDINARY_URL=https://api.cloudinary.com/v1_1/tu_cloud_universidad
CLOUDINARY_CLOUD_NAME=tu_cloud_universidad
CLOUDINARY_API_KEY=tu_api_key_universidad
CLOUDINARY_API_SECRET=tu_api_secret_universidad
```

#### **Email (SMTP de la Universidad)**
```env
# Configurar servidor de email de la universidad
MAIL_MAILER=smtp
MAIL_HOST=mail.universidad.cl
MAIL_PORT=587
MAIL_USERNAME=noreply@universidad.cl
MAIL_PASSWORD=password_email_universidad
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@universidad.cl
MAIL_FROM_NAME="Escuela de Postgrados FEN"
```

### 📧 2. Archivos de Email - Personalización

#### **`resources/views/emails/welcome-user.blade.php`**
```html
<!-- Cambiar URLs y referencias -->
<img src="{{ asset('images/FEN1.png') }}" alt="FEN Logo">
<h1>¡Bienvenid@s a la Escuela de Postgrados FEN!</h1>
<p>Facultad de Economía y Negocios - Universidad de Talca</p>

<!-- Cambiar footer -->
<p>&copy; {{ date('Y') }} Postgrado FEN - Universidad de Talca</p>
<p>Facultad de Economía y Negocios | Todos los derechos reservados</p>
```

### 👥 3. Usuarios y Seeders - Datos Reales

#### **`database/seeders/MagisterSaludSeeder.php`**
```php
// Cambiar emails a los reales de la universidad
'admin' => User::firstOrCreate(
    ['email' => 'admin.fen@universidad.cl'],
    ['name' => 'Administrador FEN', 'password' => Hash::make('password_seguro'), 'rol' => 'administrador']
),

'decano' => User::firstOrCreate(
    ['email' => 'decano.fen@universidad.cl'],
    ['name' => 'Decano FEN', 'password' => Hash::make('password_seguro'), 'rol' => 'decano']
),

'visor' => User::firstOrCreate(
    ['email' => 'visor.fen@universidad.cl'],
    ['name' => 'Usuario Visor', 'password' => Hash::make('password_seguro'), 'rol' => 'visor']
),
```

### 🖼️ 4. Assets y Archivos Estáticos

#### **Logos e Imágenes**
- ✅ **Subir logos oficiales** de la universidad a `public/images/`
- ✅ **Verificar que FEN1.png, FEN2.png, etc.** sean los oficiales
- ✅ **Actualizar favicon** con el de la universidad

#### **Archivos de Configuración**
```bash
# Regenerar cache de configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 🔐 5. Seguridad y Permisos

#### **Permisos de Archivos**
```bash
# Configurar permisos correctos
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

#### **Configuración de Servidor**
```apache
# .htaccess para Apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 📊 6. Base de Datos - Migración

#### **Backup y Migración**
```bash
# 1. Backup de desarrollo
mysqldump -u usuario_dev -p fen_platform_dev > backup_dev.sql

# 2. Crear BD de producción
mysql -u root -p
CREATE DATABASE fen_platform_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 3. Migrar datos
mysql -u usuario_prod -p fen_platform_prod < backup_dev.sql

# 4. Ejecutar migraciones en producción
php artisan migrate --force
```

### 🌐 7. Configuración de Servidor Web

#### **Nginx (Recomendado)**
```nginx
server {
    listen 80;
    server_name fen.universidad.cl;
    root /var/www/html/Web_FEN/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

#### **Apache**
```apache
<VirtualHost *:80>
    ServerName fen.universidad.cl
    DocumentRoot /var/www/html/Web_FEN/public
    
    <Directory /var/www/html/Web_FEN/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 🔄 8. Proceso de Despliegue

#### **Script de Despliegue**
```bash
#!/bin/bash
# deploy.sh

echo "🚀 Iniciando despliegue a producción..."

# 1. Backup actual
cp -r /var/www/html/Web_FEN /var/www/html/Web_FEN_backup_$(date +%Y%m%d_%H%M%S)

# 2. Actualizar código
git pull origin main

# 3. Instalar dependencias
composer install --no-dev --optimize-autoloader
npm install && npm run build

# 4. Configurar permisos
chmod -R 755 storage/ bootstrap/cache/
chown -R www-data:www-data storage/ bootstrap/cache/

# 5. Limpiar cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Migrar BD si es necesario
php artisan migrate --force

echo "✅ Despliegue completado!"
```

### 📧 9. Configuración de Email Institucional

#### **Verificar Configuración SMTP**
```php
// Test de email en tinker
php artisan tinker

Mail::raw('Test de configuración', function ($message) {
    $message->to('admin@universidad.cl')
            ->subject('Test Email FEN');
});
```

### 🔍 10. Verificaciones Post-Despliegue

#### **Checklist de Verificación**
- [ ] ✅ **URL funciona**: `https://fen.universidad.cl`
- [ ] ✅ **Login funciona** con usuarios de producción
- [ ] ✅ **Emails se envían** correctamente
- [ ] ✅ **Archivos se suben** a Cloudinary
- [ ] ✅ **Base de datos** conecta correctamente
- [ ] ✅ **Logs** se escriben sin errores
- [ ] ✅ **SSL** configurado correctamente
- [ ] ✅ **Backup automático** configurado

### 🚨 11. Consideraciones Importantes

#### **Datos Sensibles**
- ❌ **NUNCA** subir `.env` con datos de desarrollo
- ❌ **NUNCA** usar passwords débiles en producción
- ✅ **SIEMPRE** usar HTTPS en producción
- ✅ **SIEMPRE** configurar backup automático

#### **Monitoreo**
```bash
# Configurar logs de producción
tail -f storage/logs/laravel.log

# Monitorear recursos
htop
df -h
```

### 📞 12. Contacto de Soporte

#### **Información de Contacto**
- **Email**: soporte.fen@universidad.cl
- **Teléfono**: +56 X XXXX XXXX
- **Responsable**: [Nombre del Administrador]
- **Horario**: Lunes a Viernes 8:00 - 18:00

---

## 🎯 Resumen de Cambios Críticos

1. **🔧 .env** - Configurar BD, email, Cloudinary de la universidad
2. **👥 Usuarios** - Cambiar emails a los oficiales de la universidad  
3. **🖼️ Assets** - Subir logos oficiales de la universidad
4. **🌐 Dominio** - Configurar dominio oficial de la universidad
5. **🔐 Seguridad** - Configurar SSL y permisos correctos
6. **📧 Email** - Configurar SMTP institucional
7. **💾 Backup** - Configurar respaldos automáticos

¡Con esta guía tendrás todo listo para el despliegue en producción! 🚀
