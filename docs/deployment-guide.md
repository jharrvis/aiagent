# 🚀 Panduan Deploy ke Production Server

## AI Agent Platform - Deployment Guide

Dokumentasi ini menjelaskan langkah-langkah untuk men-deploy **AI Agent Platform** ke server production.

---

## 📋 Daftar Isi

1. [Persiapan Server](#1-persiapan-server)
2. [Persiapan Project](#2-persiapan-project)
3. [Deployment Steps](#3-deployment-steps)
4. [Konfigurasi Environment](#4-konfigurasi-environment)
5. [Konfigurasi Web Server](#5-konfigurasi-web-server)
6. [Setup Queue & Scheduler](#6-setup-queue--scheduler)
7. [SSL/HTTPS Setup](#7-sslhttps-setup)
8. [Monitoring & Maintenance](#8-monitoring--maintenance)
9. [Troubleshooting](#9-troubleshooting)

---

## 1. Persiapan Server

### Minimum Requirements

| Resource | Minimum | Recommended |
|----------|---------|-------------|
| **CPU** | 2 Core | 4 Core |
| **RAM** | 4 GB | 8 GB |
| **Storage** | 20 GB | 40 GB SSD |
| **OS** | Ubuntu 20.04 LTS | Ubuntu 22.04 LTS |
| **PHP** | 8.2 | 8.3+ |

### Server Dependencies

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install PHP 8.3 dan extensions
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.3 php8.3-fpm php8.3-cli php8.3-pgsql php8.3-gd \
    php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-intl \
    php8.3-bcmath php8.3-tokenizer php8.3-fileinfo

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js & NPM (untuk compile assets)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install Git
sudo apt install -y git

# Install Supervisor (untuk queue worker)
sudo apt install -y supervisor

# Install Nginx
sudo apt install -y nginx
```

---

## 2. Persiapan Project

### 2.1 Clone Repository

```bash
# Buat direktori untuk aplikasi
sudo mkdir -p /var/www/aiagent
sudo chown $USER:$USER /var/www/aiagent

# Clone repository
cd /var/www/aiagent
git clone <repository-url> .

# Atau upload file project (jika tidak menggunakan Git)
# Upload semua file ke /var/www/aiagent
```

### 2.2 Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install NPM dependencies
npm ci

# Build assets untuk production
npm run build
```

---

## 3. Deployment Steps

### 3.1 Setup Environment File

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate --force
```

### 3.2 Konfigurasi Database

```bash
# Buat database PostgreSQL
sudo -u postgres psql

CREATE DATABASE aiagent;
CREATE USER aiagent_user WITH PASSWORD 'your_secure_password';
GRANT ALL PRIVILEGES ON DATABASE aiagent TO aiagent_user;
\q

# Update file .env
nano .env
```

### 3.3 Setup Permissions

```bash
# Set permissions untuk storage dan cache
sudo chown -R www-data:www-data /var/www/aiagent/storage
sudo chown -R www-data:www-data /var/www/aiagent/bootstrap/cache
sudo chown -R www-data:www-data /var/www/aiagent/public/storage

# Set folder permissions
sudo chmod -R 775 /var/www/aiagent/storage
sudo chmod -R 775 /var/www/aiagent/bootstrap/cache
```

### 3.4 Run Migrations

```bash
# Run database migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force
```

### 3.5 Clear & Cache Configuration

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache configurations untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

## 4. Konfigurasi Environment

### 4.1 File .env Production

Edit file `.env` dengan konfigurasi berikut:

```env
# Application
APP_NAME="AI Agent Platform"
APP_ENV=production
APP_KEY=your_generated_key
APP_DEBUG=false
APP_URL=https://your-domain.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en

# Database (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=aiagent
DB_USERNAME=aiagent_user
DB_PASSWORD=your_secure_password

# Session & Cache (gunakan database untuk production)
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true

CACHE_STORE=database

# Queue
QUEUE_CONNECTION=database

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error

# Mail (configure sesuai kebutuhan)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"

# OpenRouter API
OPENROUTER_API_KEY=your_standard_api_key
OPENROUTER_MANAGEMENT_KEY=your_management_api_key
OPENROUTER_BASE_URL=https://openrouter.ai/api/v1

# Profit Multiplier (untuk billing)
AI_PROFIT_MULTIPLIER=2.0

# Google OAuth (optional)
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=${APP_URL}/auth/google/callback
```

### ⚠️ Security Notes

1. **APP_DEBUG** harus `false` di production
2. Gunakan **strong passwords** untuk database
3. Simpan **API keys** dengan aman
4. Gunakan **HTTPS** untuk semua traffic
5. Backup file `.env` secara terpisah

---

## 5. Konfigurasi Web Server

### 5.1 Nginx Configuration

Buat file konfigurasi Nginx:

```bash
sudo nano /etc/nginx/sites-available/aiagent
```

Isi dengan konfigurasi berikut:

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    
    # Redirect HTTP to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com www.your-domain.com;
    
    root /var/www/aiagent/public;
    index index.php;
    
    # SSL Configuration (akan di-setup di section 7)
    ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;
    
    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' https: 'unsafe-inline' 'unsafe-eval';" always;
    
    # Logging
    access_log /var/log/nginx/aiagent-access.log;
    error_log /var/log/nginx/aiagent-error.log error;
    
    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/json image/svg+xml;
    
    # Handle static files
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # PHP-FPM configuration
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 300;
        fastcgi_connect_timeout 60;
        fastcgi_send_timeout 180;
    }
    
    # Deny access to sensitive files
    location ~ /\.ht {
        deny all;
    }
    
    location ~ /\.env {
        deny all;
    }
    
    location ~ /composer\.(json|lock) {
        deny all;
    }
    
    location ~ /(\.git|\.github) {
        deny all;
    }
}
```

### 5.2 Enable Site

```bash
# Create symbolic link
sudo ln -s /etc/nginx/sites-available/aiagent /etc/nginx/sites-enabled/

# Remove default site
sudo rm /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

---

## 6. Setup Queue & Scheduler

### 6.1 Queue Worker dengan Supervisor

Buat file konfigurasi Supervisor:

```bash
sudo nano /etc/supervisor/conf.d/aiagent-worker.conf
```

Isi dengan:

```ini
[program:aiagent-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/aiagent/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasuser=false
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/aiagent/storage/logs/worker.log
stopwaitsecs=3600
```

Jalankan Supervisor:

```bash
# Reload Supervisor configuration
sudo supervisorctl reread
sudo supervisorctl update

# Start workers
sudo supervisorctl start aiagent-worker:*

# Check status
sudo supervisorctl status
```

### 6.2 Task Scheduler

Edit crontab:

```bash
sudo crontab -e
```

Tambahkan:

```cron
* * * * * cd /var/www/aiagent && php artisan schedule:run >> /dev/null 2>&1
```

### 6.3 Enable Scheduler di Laravel

Di file `app/Providers/AppServiceProvider.php` atau model yang membutuhkan:

```php
protected function schedule(Schedule $schedule)
{
    // Contoh: Process knowledge sources setiap 5 menit
    $schedule->job(new \App\Jobs\ProcessKnowledgeSourceJob())
             ->everyFiveMinutes();
    
    // Contoh: Clear old sessions setiap hari
    $schedule->command('session:flush')->daily();
}
```

---

## 7. SSL/HTTPS Setup

### 7.1 Install Certbot

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx
```

### 7.2 Generate SSL Certificate

```bash
# Generate certificate
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Auto-renewal test
sudo certbot renew --dry-run
```

### 7.3 Auto-Renewal

Certbot akan otomatis menambahkan cron job untuk renewal. Verify dengan:

```bash
sudo systemctl status certbot.timer
```

---

## 8. Monitoring & Maintenance

### 8.1 Logging

```bash
# Laravel logs
tail -f /var/www/aiagent/storage/logs/laravel.log

# Nginx access log
tail -f /var/log/nginx/aiagent-access.log

# Nginx error log
tail -f /var/log/nginx/aiagent-error.log

# Supervisor worker log
tail -f /var/www/aiagent/storage/logs/worker.log
```

### 8.2 Backup Database

Buat script backup:

```bash
sudo nano /var/www/aiagent/backup.sh
```

Isi dengan:

```bash
#!/bin/bash

# Backup configuration
DB_NAME="aiagent"
DB_USER="aiagent_user"
BACKUP_DIR="/var/backups/aiagent"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
pg_dump -U $DB_USER -h 127.0.0.1 $DB_NAME | gzip > $BACKUP_DIR/db_backup_$DATE.sql.gz

# Backup .env file
cp /var/www/aiagent/.env $BACKUP_DIR/env_backup_$DATE

# Delete backups older than 30 days
find $BACKUP_DIR -type f -mtime +30 -delete

echo "Backup completed: $DATE"
```

Jalankan dengan cron:

```bash
sudo crontab -e
```

Tambahkan:

```cron
0 2 * * * /var/www/aiagent/backup.sh >> /var/log/aiagent-backup.log 2>&1
```

### 8.3 Deployment Script

Buat script untuk deployment otomatis:

```bash
sudo nano /var/www/aiagent/deploy.sh
```

Isi dengan:

```bash
#!/bin/bash

echo "🚀 Starting deployment..."

cd /var/www/aiagent

# Pull latest changes
git pull origin master

# Install dependencies
composer install --optimize-autoloader --no-dev
npm ci
npm run build

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Restart queue workers
sudo supervisorctl restart aiagent-worker:*

# Reload PHP-FPM
sudo systemctl reload php8.3-fpm

echo "✅ Deployment completed!"
```

Make executable:

```bash
chmod +x /var/www/aiagent/deploy.sh
```

### 8.4 Health Check

Buat endpoint untuk monitoring:

```bash
nano /var/www/aiagent/resources/views/health.blade.php
```

```blade
<!DOCTYPE html>
<html>
<head>
    <title>Health Check</title>
    <meta http-equiv="refresh" content="60">
</head>
<body>
    <h1>System Health</h1>
    <ul>
        <li>✅ Database: {{ \DB::connection()->getPdo() ? 'Connected' : 'Disconnected' }}</li>
        <li>✅ Cache: {{ Cache::put('health_check', 'ok', 60) ? 'Working' : 'Failed' }}</li>
        <li>✅ Storage: {{ @file_exists(storage_path()) ? 'Accessible' : 'Inaccessible' }}</li>
        <li>✅ Queue: {{ \DB::table('jobs')->count() }} jobs pending</li>
        <li>✅ Last Updated: {{ now() }}</li>
    </ul>
</body>
</html>
```

Add route (hanya accessible untuk admin):

```php
// routes/web.php
Route::get('/health', function() {
    if (!auth()->check() || !auth()->user()->is_admin) {
        abort(403);
    }
    return view('health');
})->middleware(['auth', 'admin']);
```

---

## 9. Troubleshooting

### 9.1 Common Issues

#### Permission Denied

```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/aiagent/storage
sudo chmod -R 775 /var/www/aiagent/storage
```

#### Queue Not Processing

```bash
# Check Supervisor status
sudo supervisorctl status

# Restart workers
sudo supervisorctl restart aiagent-worker:*

# Check logs
tail -f /var/www/aiagent/storage/logs/worker.log
```

#### 502 Bad Gateway

```bash
# Check PHP-FPM status
sudo systemctl status php8.3-fpm

# Restart PHP-FPM
sudo systemctl restart php8.3-fpm

# Check Nginx error log
tail -f /var/log/nginx/aiagent-error.log
```

#### Database Connection Failed

```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check .env configuration
php artisan env
```

### 9.2 Debug Mode (Temporary)

Untuk debugging, enable debug mode sementara:

```bash
nano .env
```

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

**⚠️ Jangan lupa kembalikan ke `false` setelah debugging!**

### 9.3 Rollback Deployment

Jika ada masalah setelah deployment:

```bash
cd /var/www/aiagent

# Rollback database migration
php artisan migrate:rollback --force

# Atau rollback ke commit sebelumnya
git checkout <previous-commit-hash>

# Clear caches
php artisan config:clear
php artisan cache:clear
```

---

## 📞 Support

Jika mengalami masalah saat deployment:

1. Check log files di `/var/www/aiagent/storage/logs/`
2. Check Nginx error log di `/var/log/nginx/`
3. Check Supervisor status dengan `sudo supervisorctl status`
4. Pastikan semua service berjalan: `nginx`, `php8.3-fpm`, `postgresql`, `supervisor`

---

## ✅ Deployment Checklist

- [ ] Server dependencies installed
- [ ] Project files uploaded/cloned
- [ ] Dependencies installed (Composer & NPM)
- [ ] `.env` file configured
- [ ] Database created and migrated
- [ ] Storage permissions set
- [ ] Assets built (`npm run build`)
- [ ] Nginx configured
- [ ] SSL certificate installed
- [ ] Queue workers running (Supervisor)
- [ ] Scheduler configured (Cron)
- [ ] Backup script configured
- [ ] Monitoring setup
- [ ] Test deployment successful

---

**Last Updated:** February 23, 2026  
**Version:** 1.0.0
