# ==============================================================================
# SinergiEdu - Hostinger Production Deployment Script (PowerShell)
# Target: u736587297@153.92.11.159:65002 -> domains/sinergiedu.com/public_html/
# ==============================================================================

$ErrorActionPreference = "Stop"

# Konfigurasi SSH Hostinger
$SSH_HOST = "153.92.11.159"
$SSH_PORT = "65002"
$SSH_USER = "u736587297"
$REMOTE_PATH = "domains/sinergiedu.com/public_html"
$ARCHIVE_NAME = "deploy_sinergiedu.tar.gz"

Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host " 🚀 MEMULAI PROSES PACKAGING & DEPLOYMENT SINERGIEDU      " -ForegroundColor Cyan
Write-Host "==========================================================" -ForegroundColor Cyan

# 1. Build Production Asset Frontend
Write-Host "`n[1/5] Mengompilasi asset frontend (Vite)..." -ForegroundColor Yellow
npm run build

# 2. Persiapan file arsip deployment (tar)
Write-Host "`n[2/5] Mengarsipkan source code produksi..." -ForegroundColor Yellow
if (Test-Path $ARCHIVE_NAME) {
    Remove-Item $ARCHIVE_NAME -Force
}

# Daftar direktori dan file yang di-pack
$INCLUDE_ITEMS = @(
    "app",
    "bootstrap",
    "config",
    "database",
    "public",
    "resources",
    "routes",
    "storage",
    "artisan",
    "composer.json",
    "composer.lock"
)

tar -czf $ARCHIVE_NAME $INCLUDE_ITEMS

$archiveSizeMB = [math]::Round(((Get-Item $ARCHIVE_NAME).Length / 1MB), 2)
Write-Host "✓ File arsip dibuat: $ARCHIVE_NAME ($archiveSizeMB MB)" -ForegroundColor Green

# 3. Upload file ke Hostinger via SCP
Write-Host "`n[3/5] Mengunggah arsip ke server Hostinger..." -ForegroundColor Yellow
Write-Host "Silakan masukkan password SSH jika diminta:" -ForegroundColor Magenta

scp -P $SSH_PORT $ARCHIVE_NAME "${SSH_USER}@${SSH_HOST}:${REMOTE_PATH}/"

# 4. Eksekusi deployment di server Hostinger via SSH
Write-Host "`n[4/5] Mengekstrak dan mengonfigurasi aplikasi di Hostinger..." -ForegroundColor Yellow
Write-Host "Silakan masukkan password SSH jika diminta:" -ForegroundColor Magenta

$REMOTE_COMMANDS = @"
cd ${REMOTE_PATH}
echo '--> Mengekstrak file...'
tar -xzf ${ARCHIVE_NAME}
rm -f ${ARCHIVE_NAME}

echo '--> Menyiapkan direktori storage & cache...'
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo '--> Memastikan file .htaccess public root aman...'
if [ ! -f .htaccess ]; then
    cat << 'EOF' > .htaccess
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/`$1 [L]
</IfModule>
EOF
fi

echo '--> Menjalankan composer install (no-dev)...'
composer install --no-dev --optimize-autoloader --no-interaction

echo '--> Menyiapkan konfigurasi .env production...'
if [ ! -f .env ]; then
    cp .env.example .env 2>/dev/null || touch .env
fi

# Set kredensial database di .env server
sed -i 's|^DB_CONNECTION=.*|DB_CONNECTION=mysql|' .env
sed -i 's|^#\? \?DB_HOST=.*|DB_HOST=127.0.0.1|' .env
sed -i 's|^#\? \?DB_PORT=.*|DB_PORT=3306|' .env
sed -i 's|^#\? \?DB_DATABASE=.*|DB_DATABASE=u736587297_sinergiedu|' .env
sed -i 's|^#\? \?DB_USERNAME=.*|DB_USERNAME=u736587297_duskri|' .env
sed -i "s|^#\? \?DB_PASSWORD=.*|DB_PASSWORD='d7MIh?~t3qS:'|" .env
sed -i 's|^APP_ENV=.*|APP_ENV=production|' .env
sed -i 's|^APP_DEBUG=.*|APP_DEBUG=false|' .env
sed -i 's|^APP_URL=.*|APP_URL=https://sinergiedu.com|' .env
sed -i 's|^SESSION_DRIVER=.*|SESSION_DRIVER=database|' .env
sed -i 's|^CACHE_STORE=.*|CACHE_STORE=database|' .env
sed -i 's|^QUEUE_CONNECTION=.*|QUEUE_CONNECTION=database|' .env

# Generate APP_KEY jika belum ada
if ! grep -q "^APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

echo '--> Menjalankan migrasi database...'
php artisan migrate --force

echo '--> Seeding data awal role & master akademik...'
php artisan db:seed --class=DatabaseSeeder --force

echo '--> Mengaktifkan cache optimasi Laravel...'
php artisan storage:link 2>/dev/null || true
php artisan optimize

echo '--> Deployment Selesai dengan Sukses!'
"@

ssh -p $SSH_PORT "${SSH_USER}@${SSH_HOST}" $REMOTE_COMMANDS

# 5. Cleanup lokal
Write-Host "`n[5/5] Membersihkan file arsip lokal..." -ForegroundColor Yellow
if (Test-Path $ARCHIVE_NAME) {
    Remove-Item $ARCHIVE_NAME -Force
}

Write-Host "`n==========================================================" -ForegroundColor Green
Write-Host " ✓ DEPLOYMENT SELESAI! Silakan akses https://sinergiedu.com" -ForegroundColor Green
Write-Host "==========================================================" -ForegroundColor Green
