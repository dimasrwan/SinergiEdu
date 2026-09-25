#!/bin/bash
# ==============================================================================
# SinergiEdu - Hostinger Production Deployment Script (Bash / Git Bash)
# Target: u736587297@153.92.11.159:65002 -> domains/sinergiedu.com/public_html/
# ==============================================================================

set -e

SSH_HOST="153.92.11.159"
SSH_PORT="65002"
SSH_USER="u736587297"
REMOTE_PATH="domains/sinergiedu.com/public_html"
ARCHIVE_NAME="deploy_sinergiedu.tar.gz"

echo "=========================================================="
echo " 🚀 MEMULAI PROSES PACKAGING & DEPLOYMENT SINERGIEDU      "
echo "=========================================================="

# 1. Build Production Asset Frontend
echo -e "\n[1/5] Mengompilasi asset frontend (Vite)..."
npm run build

# 2. Persiapan file arsip
echo -e "\n[2/5] Mengarsipkan source code produksi..."
rm -f ${ARCHIVE_NAME}

tar -czf ${ARCHIVE_NAME} \
    app \
    bootstrap \
    config \
    database \
    public \
    resources \
    routes \
    storage \
    artisan \
    composer.json \
    composer.lock

echo "✓ File arsip berhasil dibuat: ${ARCHIVE_NAME}"

# 3. Upload file via SCP
echo -e "\n[3/5] Mengunggah arsip ke server Hostinger..."
scp -P ${SSH_PORT} ${ARCHIVE_NAME} ${SSH_USER}@${SSH_HOST}:${REMOTE_PATH}/

# 4. Eksekusi deployment di server Hostinger via SSH
echo -e "\n[4/5] Mengekstrak dan mengonfigurasi aplikasi di Hostinger..."
ssh -p ${SSH_PORT} ${SSH_USER}@${SSH_HOST} << 'EOF'
cd domains/sinergiedu.com/public_html
echo '--> Mengekstrak file...'
tar -xzf deploy_sinergiedu.tar.gz
rm -f deploy_sinergiedu.tar.gz

echo '--> Menyiapkan direktori storage & cache...'
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo '--> Memastikan file .htaccess public root aman...'
if [ ! -f .htaccess ]; then
    cat << 'HTACCESS' > .htaccess
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
HTACCESS
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
EOF

# 5. Cleanup lokal
echo -e "\n[5/5] Membersihkan file arsip lokal..."
rm -f ${ARCHIVE_NAME}

echo "=========================================================="
echo " ✓ DEPLOYMENT SELESAI! Silakan akses https://sinergiedu.com"
echo "=========================================================="
