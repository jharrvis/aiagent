# 🚀 Panduan Deploy ke Production Server

## AI Agent Platform - Deployment Guide with HestiaCP

Dokumentasi ini menjelaskan langkah-langkah untuk men-deploy **AI Agent Platform** ke server production menggunakan **HestiaCP** pada Ubuntu.

---

## 📋 Daftar Isi

1. [Persiapan Server](#1-persiapan-server)
2. [Install HestiaCP](#2-install-hestiacp)
3. [Setup Domain & SSL di HestiaCP](#3-setup-domain--ssl-di-hestiacp)
4. [Persiapan Project](#4-persiapan-project)
5. [Konfigurasi Database](#5-konfigurasi-database)
6. [Konfigurasi Environment](#6-konfigurasi-environment)
7. [Setup PHP-FPM](#7-setup-php-fpm)
8. [Setup Queue Worker](#8-setup-queue-worker)
9. [Setup Scheduler](#9-setup-scheduler)
10. [Backup & Maintenance](#10-backup--maintenance)
11. [Monitoring & Troubleshooting](#11-monitoring--troubleshooting)

---

## 1. Persiapan Server

### Minimum Requirements

| Resource | Minimum | Recommended |
|----------|---------|-------------|
| **CPU** | 2 Core | 4 Core |
| **RAM** | 4 GB | 8 GB |
| **Storage** | 20 GB | 40 GB SSD |
| **OS** | Ubuntu 20.04 LTS | Ubuntu 22.04 LTS |

### Update Server

```bash
# Login sebagai root atau user dengan sudo
ssh root@your-server-ip

# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y git curl wget unzip
```

---

## 2. Install HestiaCP

### 2.1 Download & Install

```bash
# Download HestiaCP installation script
cd /tmp
wget https://raw.githubusercontent.com/hestiacp/hestiacp/release/install/hst-install.sh

# Install HestiaCP dengan konfigurasi default
# Email akan digunakan untuk login admin
bash hst-install.sh --email your-email@example.com
```

### 2.2 Install dengan Opsi Lengkap

```bash
# Install dengan semua fitur (recommended)
bash hst-install.sh \
    --email your-email@example.com \
    --hostname your-domain.com \
    --password 'YourSecurePassword123!' \
    --nginx \
    --phpfpm \
    --multiphp \
    --postgresql \
    --redis \
    --letsencrypt \
    --api
```

### 2.3 Akses HestiaCP Admin Panel

Setelah instalasi selesai, Anda akan melihat informasi seperti:

```
Grand Total:    1 package(s)

Installation Completed!

Admin panel: https://your-server-ip:8083/
Username: admin
Password: YourSecurePassword123!
```

**⚠️ Penting:**
- Catat username dan password
- Akses panel admin di `https://your-server-ip:8083/`
- Ganti password default setelah login pertama kali

### 2.4 Install PHP 8.3

```bash
# Login ke HestiaCP admin panel
# Navigasi ke SERVER → Settings → Configure

# Atau via CLI untuk install PHP 8.3
sudo /usr/local/hestia/bin/v-add-web-php 8.3
```

Verify PHP versions:

```bash
ls /usr/bin/php*
```

---

## 3. Setup Domain & SSL di HestiaCP

### 3.1 Login ke HestiaCP

1. Buka `https://your-server-ip:8083/`
2. Login dengan username `admin` dan password yang sudah dibuat

### 3.2 Tambah User (Optional)

Untuk keamanan, buat user terpisah untuk aplikasi:

1. Klik **USER** → **Add User**
2. Isi informasi:
   - **Username:** `aiagent`
   - **Email:** your-email@example.com
   - **Password:** SecurePassword123!
   - **Role:** User

### 3.3 Tambah Domain/Package

1. Login sebagai user `aiagent`
2. Klik **WEB** → **Add Web Domain**
3. Isi konfigurasi:
   - **Domain:** `your-domain.com`
   - **Alias:** `www.your-domain.com`
   - **IP Address:** Pilih IP server
   - **Enable SSL:** ✓ Check
   - **SSL Provider:** Let's Encrypt
   - **Enable AutoSSL:** ✓ Check
   - **PHP Support:** ✓ Check
   - **PHP Version:** 8.3
   - **PHP FPM Mode:** Enabled

4. Klik **Save**

### 3.4 Setup SSL Certificate

HestiaCP akan otomatis generate SSL certificate dengan Let's Encrypt.

Verify SSL:
```bash
# Check SSL certificate
sudo /usr/local/hestia/bin/v-list-web-domain your-domain.com aiagent json
```

### 3.5 Setup Database Server

1. Klik **DB** → **Add Database**
2. Isi konfigurasi:
   - **Database Name:** `aiagent`
   - **Database User:** `aiagent_user`
   - **Password:** SecureDatabasePassword123!
   - **Type:** PostgreSQL
   - **Charset:** utf8

3. Klik **Save**

Catat kredensial database untuk konfigurasi nanti.

---

## 4. Persiapan Project

### 4.1 Upload Project Files

**Option A: Git Clone (Recommended)**

```bash
# Login via SSH sebagai user aiagent
ssh aiagent@your-server-ip

# Navigate ke web directory
cd /home/aiagent/web/your-domain.com/public_html

# Clone repository
git clone <repository-url> .

# Atau jika sudah ada file, pindah ke folder
mv /path/to/project/* .
```

**Option B: Upload via File Manager**

1. Login ke HestiaCP
2. Navigasi ke **FILES** → **Home** → `aiagent` → `web` → `your-domain.com` → `public_html`
3. Upload file project (zip) dan extract

### 4.2 Install Dependencies

```bash
cd /home/aiagent/web/your-domain.com/public_html

# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Install NPM dependencies
npm ci

# Build assets untuk production
npm run build
```

### 4.3 Setup Folder Structure

```bash
# Pastikan struktur folder benar
# public_html/ berisi semua file Laravel
# Atau atur document root ke public/

# Option 1: Pindahkan semua ke public_html (recommended untuk HestiaCP)
# Sudah dilakukan di step 4.1

# Option 2: Ubah document root ke public/
# Di HestiaCP: WEB → your-domain.com → Edit → Document Root
# Set ke: /public_html/public
```

---

## 5. Konfigurasi Database

### 5.1 Get Database Credentials

Dari HestiaCP:
1. Login ke HestiaCP
2. Klik **DB** → **your-domain.com_aiagent**
3. Catat informasi:
   - **Database:** `your-domain.com_aiagent` atau `aiagent`
   - **Username:** `aiagent_user`
   - **Password:** (yang sudah dibuat)
   - **Host:** `localhost`

### 5.2 Run Migrations

```bash
cd /home/aiagent/web/your-domain.com/public_html

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit

# Run migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force
```

---

## 6. Konfigurasi Environment

### 6.1 Setup .env File

```bash
cd /home/aiagent/web/your-domain.com/public_html

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate --force
```

### 6.2 Edit .env File

```bash
nano .env
```

Isi dengan konfigurasi berikut:

```env
# Application
APP_NAME="Assisten CEO"
APP_ENV=production
APP_KEY=your_generated_key
APP_DEBUG=false
APP_URL=https://your-domain.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en

# Database (dari HestiaCP)
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=your-domain.com_aiagent
DB_USERNAME=aiagent_user
DB_PASSWORD=your_database_password

# Session & Cache
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

# Profit Multiplier
AI_PROFIT_MULTIPLIER=2.0

# Google OAuth (optional)
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=${APP_URL}/auth/google/callback
```

### 6.3 Cache Configuration

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 7. Setup PHP-FPM

### 7.1 Configure PHP di HestiaCP

1. Login ke HestiaCP
2. **WEB** → **your-domain.com** → **Edit**
3. Set konfigurasi PHP:
   - **PHP Version:** 8.3
   - **PHP FPM Mode:** Enabled
   - **PHP Settings:**
     ```
     memory_limit = 512M
     max_execution_time = 300
     upload_max_filesize = 64M
     post_max_size = 64M
     max_input_time = 300
     max_input_vars = 3000
     ```

### 7.2 Custom PHP Configuration (Optional)

Untuk custom PHP configuration:

```bash
# Create custom php.ini
sudo nano /etc/php/8.3/fpm/pool.d/aiagent.conf
```

Isi dengan:

```ini
[aiagent]
pm = dynamic
pm.max_children = 10
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 5
pm.max_requests = 500

php_admin_value[open_basedir] = /home/aiagent/web/your-domain.com/public_html:/tmp
php_admin_value[upload_max_filesize] = 64M
php_admin_value[max_execution_time] = 300
php_admin_value[memory_limit] = 512M
```

Restart PHP-FPM:

```bash
sudo systemctl restart php8.3-fpm
```

---

## 8. Setup Queue Worker

### 8.1 Install Supervisor

```bash
# Install supervisor
sudo apt install -y supervisor
```

### 8.2 Create Supervisor Configuration

```bash
sudo nano /etc/supervisor/conf.d/aiagent-worker.conf
```

Isi dengan:

```ini
[program:aiagent-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/aiagent/web/your-domain.com/public_html/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasuser=false
killasgroup=true
user=aiagent
numprocs=2
redirect_stderr=true
stdout_logfile=/home/aiagent/web/your-domain.com/public_html/storage/logs/worker.log
stopwaitsecs=3600
```

### 8.3 Start Supervisor

```bash
# Reload supervisor configuration
sudo supervisorctl reread
sudo supervisorctl update

# Start workers
sudo supervisorctl start aiagent-worker:*

# Check status
sudo supervisorctl status
```

### 8.4 Manage Workers via HestiaCP

Anda juga bisa manage supervisor tasks dari HestiaCP:

1. **SERVER** → **Cron Jobs**
2. Add cron untuk monitoring worker

---

## 9. Setup Scheduler

### 9.1 Enable Laravel Scheduler

Edit crontab:

```bash
sudo crontab -e
```

Tambahkan:

```cron
* * * * * cd /home/aiagent/web/your-domain.com/public_html && php artisan schedule:run >> /dev/null 2>&1
```

### 9.2 Setup Cron Jobs di HestiaCP

1. Login ke HestiaCP
2. **CRON** → **Add Cron Job**
3. Isi konfigurasi:
   - **Minute:** `*`
   - **Hour:** `*`
   - **Day:** `*`
   - **Month:** `*`
   - **Weekday:** `*`
   - **Command:** `cd /home/aiagent/web/your-domain.com/public_html && php artisan schedule:run`
   - **User:** `aiagent`

4. Klik **Save**

### 9.3 Configure Scheduled Jobs

Di file `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule): void
{
    // Process knowledge sources setiap 5 menit
    $schedule->job(new \App\Jobs\ProcessKnowledgeSourceJob())
             ->everyFiveMinutes();
    
    // Clear old sessions setiap hari
    $schedule->command('session:flush')->daily();
    
    // Backup database setiap minggu
    $schedule->command('backup:run')->weekly();
}
```

---

## 10. Backup & Maintenance

### 10.1 Setup Automated Backup via HestiaCP

HestiaCP sudah include backup functionality:

1. **CRON** → **Add Cron Job**
2. Setup backup command:

```bash
#!/bin/bash
# /home/aiagent/backup.sh

BACKUP_DIR="/home/aiagent/backups"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="your-domain.com_aiagent"
DB_USER="aiagent_user"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
pg_dump -U $DB_USER $DB_NAME | gzip > $BACKUP_DIR/db_backup_$DATE.sql.gz

# Backup .env file
cp /home/aiagent/web/your-domain.com/public_html/.env $BACKUP_DIR/env_backup_$DATE

# Delete backups older than 30 days
find $BACKUP_DIR -type f -mtime +30 -delete

echo "Backup completed: $DATE"
```

3. Make executable:
```bash
chmod +x /home/aiagent/backup.sh
```

4. Add to HestiaCP Cron:
   - **CRON** → **Add Cron Job**
   - **Schedule:** `0 2 * * *` (daily at 2 AM)
   - **Command:** `/home/aiagent/backup.sh`

### 10.2 HestiaCP Built-in Backups

1. **BACKUP** → **Create Backup**
2. Select items to backup:
   - ✓ Web domains
   - ✓ Databases
   - ✓ Cron jobs
   - ✓ SSL certificates

3. **Backup Location:** Local atau Remote (S3, FTP, etc.)
4. Klik **Backup**

### 10.3 Deployment Script

Buat script untuk deployment otomatis:

```bash
nano /home/aiagent/deploy.sh
```

Isi dengan:

```bash
#!/bin/bash

echo "🚀 Starting deployment..."

cd /home/aiagent/web/your-domain.com/public_html

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

echo "✅ Deployment completed!"
```

Make executable:

```bash
chmod +x /home/aiagent/deploy.sh
```

Usage:

```bash
# Run deployment
./deploy.sh

# Or with sudo
sudo -u aiagent ./deploy.sh
```

---

## 11. Monitoring & Troubleshooting

### 11.1 HestiaCP Monitoring

**Dashboard:**
- Login ke HestiaCP
- Lihat resource usage di dashboard
- Monitor web, database, dan cron jobs

**Logs:**
- **WEB** → **your-domain.com** → **Logs**
- View access log dan error log langsung dari panel

### 11.2 Application Logs

```bash
# Laravel logs
tail -f /home/aiagent/web/your-domain.com/public_html/storage/logs/laravel.log

# Queue worker logs
tail -f /home/aiagent/web/your-domain.com/public_html/storage/logs/worker.log

# Nginx logs (via HestiaCP)
# WEB → your-domain.com → Logs
```

### 11.3 Common Issues

#### Permission Denied

```bash
# Fix permissions via HestiaCP
# FILES → Select folder → Edit → Permissions
# Set: 755 untuk folders, 644 untuk files

# Atau via CLI
sudo chown -R aiagent:aiagent /home/aiagent/web/your-domain.com/public_html/storage
sudo chmod -R 775 /home/aiagent/web/your-domain.com/public_html/storage
```

#### Queue Not Processing

```bash
# Check Supervisor status
sudo supervisorctl status

# Restart workers
sudo supervisorctl restart aiagent-worker:*

# Check logs
tail -f /home/aiagent/web/your-domain.com/public_html/storage/logs/worker.log
```

#### 502 Bad Gateway

```bash
# Check PHP-FPM status
sudo systemctl status php8.3-fpm

# Restart PHP-FPM
sudo systemctl restart php8.3-fpm

# Check via HestiaCP
# WEB → your-domain.com → Edit → Restart PHP-FPM
```

#### Database Connection Failed

```bash
# Check database credentials di .env
nano .env

# Test connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check database via HestiaCP
# DB → your-domain.com_aiagent → phpPgAdmin
```

### 11.4 Health Check Endpoint

Buat route untuk monitoring:

```php
// routes/web.php
Route::get('/health', function() {
    if (!auth()->check() || !auth()->user()->is_admin) {
        abort(403);
    }
    
    return response()->json([
        'status' => 'healthy',
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
        'cache' => Cache::put('health_check', 'ok', 60) ? 'working' : 'failed',
        'queue_jobs' => DB::table('jobs')->count(),
        'timestamp' => now(),
    ]);
})->middleware(['auth', 'admin']);
```

Access: `https://your-domain.com/health`

### 11.5 Resource Monitoring via HestiaCP

1. **SERVER** → **Dashboard**
   - CPU usage
   - Memory usage
   - Disk usage
   - Network traffic

2. **WEB** → **your-domain.com**
   - Hit count
   - Bandwidth usage
   - Disk usage

3. **DB** → **your-domain.com_aiagent**
   - Database size
   - Query statistics (via phpPgAdmin)

---

## ✅ Deployment Checklist HestiaCP

### Pre-Deployment
- [ ] Server Ubuntu installed
- [ ] HestiaCP installed and configured
- [ ] Domain pointed to server IP
- [ ] SSL certificate generated
- [ ] Database created via HestiaCP
- [ ] PHP 8.3 installed

### Deployment
- [ ] Project files uploaded/cloned
- [ ] Dependencies installed (Composer & NPM)
- [ ] `.env` file configured
- [ ] Database migrated
- [ ] Storage permissions set
- [ ] Assets built (`npm run build`)
- [ ] PHP-FPM configured
- [ ] Queue workers running (Supervisor)
- [ ] Scheduler configured (Cron)

### Post-Deployment
- [ ] Test website access
- [ ] Test database connection
- [ ] Test queue processing
- [ ] Test file upload
- [ ] Test email sending
- [ ] Backup configured
- [ ] Monitoring setup
- [ ] SSL working (HTTPS)

---

## 📞 HestiaCP Resources

- **Admin Panel:** `https://your-server-ip:8083/`
- **Documentation:** https://docs.hestiacp.com/
- **File Manager:** HestiaCP → FILES
- **Database Manager:** HestiaCP → DB → phpPgAdmin
- **Logs:** HestiaCP → WEB → Logs

---

**Last Updated:** February 23, 2026  
**Version:** 2.0.0 (HestiaCP Edition)
