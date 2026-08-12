# SinergiEdu

SinergiEdu adalah platform Sistem Informasi Manajemen Akademik terpadu yang dirancang untuk menghubungkan dan memudahkan pengelolaan data sekolah di antara semua pemangku kepentingan: Administrator, Guru, Siswa, Orang Tua, Waka Kurikulum, Pengawas, dan Kepala Sekolah/Madrasah.

## Tech Stack

- **Framework Backend**: Laravel 11 (PHP 8.2+)
- **Frontend/Templating**: Blade Components
- **Styling**: Tailwind CSS (Native/Mobile-First)
- **Database**: MySQL / MariaDB
- **Build Tool**: Vite

## Role Aplikasi

Sistem ini memiliki berbagai modul yang diatur berdasarkan peran pengguna (Role-Based Access Control):
1. **Admin**: Pengelola sistem (Master Data Akademik, Pengguna, dan Konfigurasi).
2. **Guru**: Mengelola kelas, materi, penilaian, dan absensi.
3. **Siswa**: Melihat data akademik, rapor, tugas, dan aktivitas sekolah.
4. **Orang Tua**: Memantau perkembangan dan laporan akademik anak.
5. **Waka Kurikulum**: Merencanakan materi dan memantau kalender pendidikan.
6. **Pengawas / Kepala Sekolah**: Supervisi, evaluasi, dan pelaporan (Monitoring).

## Installation

Pastikan sistem Anda telah terpasang:
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

Ikuti langkah instalasi berikut:

```bash
# 1. Clone repository ini
git clone https://github.com/your-username/SinergiEdu.git
cd SinergiEdu

# 2. Install dependencies PHP
composer install

# 3. Setup environment configuration
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database di .env (Sesuaikan kredensial Anda)
# DB_DATABASE=sinergiedu
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan migrasi dan seeder
php artisan migrate:fresh --seed

# 7. Install dependencies NPM & Build
npm install
npm run build
```

## Development Command

Jalankan aplikasi di lingkungan lokal:

```bash
# Jalankan development server PHP
php artisan serve

# Jalankan Vite dev server di terminal/tab baru
npm run dev
```

## Testing

Aplikasi ini dilengkapi pengujian untuk memastikan setiap rute dan fitur berjalan stabil. Untuk menjalankan *test suite*:
```bash
php artisan test
```

## Build Production Assets

Untuk merilis ke server produksi, jalankan perintah ini untuk melakukan kompilasi optimasi *asset*:
```bash
npm run build
```
Pastikan Anda juga melakukan optimalisasi aplikasi:
```bash
php artisan optimize
```
