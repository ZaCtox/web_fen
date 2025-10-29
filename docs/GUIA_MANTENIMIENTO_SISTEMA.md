# 🔧 Guía de Mantenimiento del Sistema - Web FEN

## 📋 Descripción

Esta guía proporciona instrucciones completas para el **mantenimiento preventivo y correctivo** del Sistema de Gestión Académica Web FEN. Incluye procedimientos de backup, actualizaciones, monitoreo y resolución de problemas avanzados.

---

## 🎯 Objetivos del Mantenimiento

### **Mantenimiento Preventivo**
- **Prevenir fallos** del sistema
- **Optimizar performance**
- **Mantener seguridad** actualizada
- **Preservar integridad** de datos

### **Mantenimiento Correctivo**
- **Resolver problemas** identificados
- **Restaurar funcionalidad** perdida
- **Corregir errores** del sistema
- **Recuperar datos** perdidos

---

## 📅 Cronograma de Mantenimiento

### **Mantenimiento Diario**
- ✅ **Verificar logs** del sistema
- ✅ **Monitorear espacio** en disco
- ✅ **Revisar backups** automáticos
- ✅ **Verificar conectividad** de base de datos

### **Mantenimiento Semanal**
- ✅ **Limpiar logs** antiguos
- ✅ **Optimizar base** de datos
- ✅ **Verificar actualizaciones** de seguridad
- ✅ **Revisar métricas** de performance

### **Mantenimiento Mensual**
- ✅ **Backup completo** del sistema
- ✅ **Actualizar dependencias**
- ✅ **Revisar configuración** de seguridad
- ✅ **Generar reportes** de mantenimiento

### **Mantenimiento Trimestral**
- ✅ **Auditoría de seguridad**
- ✅ **Actualización mayor** del sistema
- ✅ **Revisión de arquitectura**
- ✅ **Planificación de mejoras**

---

## 💾 Sistema de Backup

### **Backup Automático Diario**
```bash
#!/bin/bash
# backup_daily.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/web_fen"
DB_NAME="web_fen"

# Crear directorio de backup
mkdir -p $BACKUP_DIR

# Backup de base de datos
mysqldump -u web_fen_user -p$DB_PASSWORD $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# Backup de archivos
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/web_fen/storage/app/public

# Backup de configuración
cp /var/www/web_fen/.env $BACKUP_DIR/env_$DATE.backup

# Limpiar backups antiguos (más de 30 días)
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
find $BACKUP_DIR -name "*.backup" -mtime +30 -delete

echo "Backup completado: $DATE"
```

### **Backup Manual Completo**
```bash
# Backup completo del sistema
php artisan backup:run

# Backup de base de datos específico
php artisan db:backup

# Backup de archivos de usuario
php artisan backup:files
```

### **Verificación de Backups**
```bash
# Verificar integridad de backup
mysql -u web_fen_user -p$DB_PASSWORD -e "USE web_fen; SHOW TABLES;"

# Verificar archivos de backup
tar -tzf /backups/web_fen/files_*.tar.gz

# Verificar logs de backup
tail -f /var/log/backup.log
```

---

## 🔄 Actualizaciones del Sistema

### **Actualización de Dependencias PHP**
```bash
# Actualizar Composer
composer update

# Actualizar solo dependencias de producción
composer update --no-dev --optimize-autoloader

# Verificar vulnerabilidades
composer audit
```

### **Actualización de Dependencias Node.js**
```bash
# Actualizar NPM
npm update

# Actualizar solo dependencias de producción
npm ci --production

# Verificar vulnerabilidades
npm audit
```

### **Actualización de Laravel**
```bash
# Verificar versión actual
php artisan --version

# Actualizar Laravel
composer update laravel/framework

# Ejecutar migraciones
php artisan migrate

# Limpiar cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### **Actualización de Base de Datos**
```bash
# Ejecutar migraciones pendientes
php artisan migrate

# Verificar estado de migraciones
php artisan migrate:status

# Rollback de migración específica
php artisan migrate:rollback --step=1

# Reset completo de migraciones
php artisan migrate:reset
```

---

## 📊 Monitoreo del Sistema

### **Monitoreo de Performance**
```bash
# Verificar uso de CPU
top -p $(pgrep php-fpm)

# Verificar uso de memoria
free -h

# Verificar espacio en disco
df -h

# Verificar procesos PHP
ps aux | grep php
```

### **Monitoreo de Logs**
```bash
# Verificar logs de Laravel
tail -f storage/logs/laravel.log

# Verificar logs de Apache/Nginx
tail -f /var/log/apache2/error.log
tail -f /var/log/nginx/error.log

# Verificar logs de PHP
tail -f /var/log/php8.1-fpm.log

# Buscar errores críticos
grep -i "error\|fatal\|critical" storage/logs/laravel.log
```

### **Monitoreo de Base de Datos**
```bash
# Verificar conexiones activas
mysql -u web_fen_user -p$DB_PASSWORD -e "SHOW PROCESSLIST;"

# Verificar tamaño de base de datos
mysql -u web_fen_user -p$DB_PASSWORD -e "SELECT table_schema AS 'Database', ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)' FROM information_schema.tables GROUP BY table_schema;"

# Verificar tablas con más registros
mysql -u web_fen_user -p$DB_PASSWORD -e "SELECT table_name, table_rows FROM information_schema.tables WHERE table_schema = 'web_fen' ORDER BY table_rows DESC;"
```

---

## 🔧 Optimización del Sistema

### **Optimización de Base de Datos**
```bash
# Optimizar todas las tablas
mysql -u web_fen_user -p$DB_PASSWORD -e "USE web_fen; OPTIMIZE TABLE *;"

# Analizar tablas
mysql -u web_fen_user -p$DB_PASSWORD -e "USE web_fen; ANALYZE TABLE *;"

# Verificar integridad
mysql -u web_fen_user -p$DB_PASSWORD -e "USE web_fen; CHECK TABLE *;"
```

### **Optimización de Cache**
```bash
# Limpiar cache de Laravel
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Regenerar cache optimizado
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Limpiar cache de OPcache
php artisan opcache:clear
```

### **Optimización de Archivos**
```bash
# Limpiar archivos temporales
find /var/www/web_fen/storage/app -name "*.tmp" -delete
find /var/www/web_fen/storage/framework -name "*.tmp" -delete

# Limpiar logs antiguos
find /var/www/web_fen/storage/logs -name "*.log" -mtime +30 -delete

# Optimizar imágenes
find /var/www/web_fen/public/images -name "*.jpg" -exec jpegoptim --max=85 {} \;
```

---

## 🚨 Resolución de Problemas Avanzados

### **Problemas de Performance**
```bash
# Identificar consultas lentas
mysql -u web_fen_user -p$DB_PASSWORD -e "SHOW PROCESSLIST;"

# Verificar índices faltantes
mysql -u web_fen_user -p$DB_PASSWORD -e "USE web_fen; SHOW INDEX FROM table_name;"

# Analizar consultas específicas
mysql -u web_fen_user -p$DB_PASSWORD -e "EXPLAIN SELECT * FROM table_name WHERE condition;"
```

### **Problemas de Memoria**
```bash
# Verificar límites de PHP
php -i | grep memory_limit

# Verificar uso de memoria por proceso
ps aux | grep php-fpm | awk '{sum+=$6} END {print sum/1024 " MB"}'

# Aumentar límite de memoria temporalmente
php -d memory_limit=512M artisan command
```

### **Problemas de Conexión**
```bash
# Verificar conectividad de base de datos
mysql -u web_fen_user -p$DB_PASSWORD -e "SELECT 1;"

# Verificar puertos abiertos
netstat -tlnp | grep :3306
netstat -tlnp | grep :80
netstat -tlnp | grep :443

# Verificar DNS
nslookup domain.com
```

---

## 🔐 Mantenimiento de Seguridad

### **Actualización de Seguridad**
```bash
# Actualizar sistema operativo
sudo apt update && sudo apt upgrade -y

# Actualizar PHP
sudo apt install php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring

# Verificar vulnerabilidades
composer audit
npm audit
```

### **Configuración de Firewall**
```bash
# Configurar UFW
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable

# Verificar reglas
sudo ufw status verbose
```

### **Monitoreo de Seguridad**
```bash
# Verificar logs de acceso
tail -f /var/log/apache2/access.log
tail -f /var/log/nginx/access.log

# Buscar intentos de intrusión
grep -i "failed\|denied\|blocked" /var/log/auth.log

# Verificar permisos de archivos
find /var/www/web_fen -type f -perm 777
```

---

## 📋 Checklist de Mantenimiento

### **✅ Mantenimiento Diario**
- [ ] Verificar logs del sistema
- [ ] Monitorear espacio en disco
- [ ] Revisar backups automáticos
- [ ] Verificar conectividad de base de datos
- [ ] Revisar métricas de performance

### **✅ Mantenimiento Semanal**
- [ ] Limpiar logs antiguos
- [ ] Optimizar base de datos
- [ ] Verificar actualizaciones de seguridad
- [ ] Revisar métricas de performance
- [ ] Verificar integridad de backups

### **✅ Mantenimiento Mensual**
- [ ] Backup completo del sistema
- [ ] Actualizar dependencias
- [ ] Revisar configuración de seguridad
- [ ] Generar reportes de mantenimiento
- [ ] Auditar permisos de archivos

### **✅ Mantenimiento Trimestral**
- [ ] Auditoría de seguridad completa
- [ ] Actualización mayor del sistema
- [ ] Revisión de arquitectura
- [ ] Planificación de mejoras
- [ ] Documentación de cambios

---

## 🛠️ Herramientas de Mantenimiento

### **Herramientas del Sistema**
- **Laravel Artisan** - Comandos de mantenimiento
- **Composer** - Gestión de dependencias
- **NPM** - Gestión de assets
- **MySQL** - Gestión de base de datos

### **Herramientas Externas**
- **htop** - Monitoreo de procesos
- **iotop** - Monitoreo de I/O
- **nethogs** - Monitoreo de red
- **logrotate** - Rotación de logs

### **Scripts Automatizados**
- **backup_daily.sh** - Backup automático
- **cleanup_logs.sh** - Limpieza de logs
- **optimize_db.sh** - Optimización de BD
- **security_check.sh** - Verificación de seguridad

---

## 📞 Soporte y Escalación

### **Niveles de Soporte**
1. **Nivel 1:** Problemas básicos del sistema
2. **Nivel 2:** Problemas de configuración
3. **Nivel 3:** Problemas de arquitectura
4. **Nivel 4:** Problemas críticos del sistema

### **Contacto de Emergencia**
- **Problemas críticos:** Contactar Director Administrativo
- **Problemas técnicos:** Contactar equipo de desarrollo
- **Problemas de seguridad:** Activar protocolo de seguridad
- **Problemas de datos:** Contactar administrador de BD

---

## 📊 Reportes de Mantenimiento

### **Reportes Automáticos**
- **Reportes diarios** de performance
- **Reportes semanales** de seguridad
- **Reportes mensuales** de mantenimiento
- **Alertas** de problemas críticos

### **Métricas Clave**
- **Uptime** del sistema
- **Tiempo de respuesta** promedio
- **Uso de recursos** (CPU, memoria, disco)
- **Errores** por día/semana/mes

---

**Guía:** 🔧 **Mantenimiento del Sistema**  
**Aplicable a:** **ADMINISTRADORES TÉCNICOS**  
**Última Actualización:** Octubre 2025  
**Versión:** 1.0.0
