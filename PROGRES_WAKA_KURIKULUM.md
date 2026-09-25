# Progres Pengembangan Role Waka Kurikulum

## Selesai Hari Ini
1. **Dashboard Waka**:
   - Berhasil mengubah `DashboardController` untuk mengambil data statistik nyata (total siswa, kelas, rata-rata nilai, jumlah pertemuan).
   - Mengupdate `dashboard.blade.php` agar menampilkan data dinamis.
2. **Monitoring**:
   - Integrasi detail hafalan (Juz/Ayat) pada `student-progress.blade.php`.
   - Sinkronisasi tabel *Action Plan* kolaboratif (Waka, Guru, Orang Tua) di `student-progress.blade.php`.
   - Upgrade fitur ekspor ke `.xlsx` menggunakan library `openspout` di `MonitoringController`.
   - Implementasi logika status tugas "Tepat Waktu/Terlambat" dan tampilan nilai per *submission* di `assignment.blade.php`.
   - Menambahkan kolom `submitted_at` pada tabel `assignment_submissions` melalui migrasi database.
3. **Infrastruktur**:
   - Mengaktifkan ekstensi `zip` di `php.ini` untuk mendukung fitur `.xlsx`.

## Todo Selanjutnya
1. [ ] **Partisipasi Kelas**: Menyempurnakan perhitungan persentase partisipasi di dashboard monitoring kelas.
2. [ ] **Filter Monitoring**: Implementasi filter tahun ajaran, semester, dan status pengumpulan di rute monitoring.
3. [ ] **Refleksi Siswa**: Integrasi data modul refleksi siswa ke halaman profil/perkembangan siswa.
4. [ ] **Dashboard Lanjutan**: Implementasi deteksi "siswa berisiko" dan grafik tren capaian.
5. [ ] **PDF & Cetak**: Implementasi fitur cetak laporan (PDF) dengan kop sekolah.
6. [ ] **Testing**: Pembuatan *unit test* spesifik Waka.

## Konteks Penting
- Environment sekarang mendukung `ext-zip` (sudah diaktifkan).
- Semua perubahan dibatasi hanya pada scope `WakaKurikulum`.
