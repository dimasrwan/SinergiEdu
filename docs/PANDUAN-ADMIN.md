# Buku Panduan Pengguna — Role Admin (SinergiEdu)

Panduan ini disusun khusus untuk **Administrator sekolah/madrasah** pengelola aplikasi **SinergiEdu**. Semua langkah ditulis sederhana, lengkap, dan bertahap sehingga mudah diikuti oleh siapa pun — termasuk yang baru pertama kali membuka aplikasi.

---

## Daftar Isi

1. [Tentang Buku Panduan Ini](#1-tentang-buku-panduan-ini)
2. [Mengenal SinergiEdu dan Peran Admin](#2-mengenal-sinergiedu-dan-peran-admin)
3. [Cara Masuk (Login)](#3-cara-masuk-login)
4. [Mengenal Halaman Dashboard](#4-mengenal-halaman-dashboard)
5. [Mengelola Tahun Ajaran](#5-mengelola-tahun-ajaran)
6. [Mengelola Semester](#6-mengelola-semester)
7. [Mengelola Mata Pelajaran](#7-mengelola-mata-pelajaran)
8. [Mengelola Kelas (Rombel)](#8-mengelola-kelas-rombel)
9. [Mengelola Guru](#9-mengelola-guru)
10. [Mengelola Siswa](#10-mengelola-siswa)
11. [Mengelola Orang Tua / Wali](#11-mengelola-orang-tua--wali)
12. [Mengelola Waka Kurikulum](#12-mengelola-waka-kurikulum)
13. [Mengelola Pengawas](#13-mengelola-pengawas)
14. [Mengelola Kepala Sekolah / Madrasah](#14-mengelola-kepala-sekolah--madrasah)
15. [Mengelola Komite Sekolah](#15-mengelola-komite-sekolah)
16. [Penempatan Siswa ke Kelas](#16-penempatan-siswa-ke-kelas)
17. [Penugasan Guru (Guru Mengampu)](#17-penugasan-guru-guru-mengampu)
18. [Pengaturan Sistem (Profil Sekolah)](#18-pengaturan-sistem-profil-sekolah)
19. [Tips, Aturan, dan Pertanyaan Umum](#19-tips-aturan-dan-pertanyaan-umum)

---

## 1. Tentang Buku Panduan Ini

- **Sasaran pembaca:** operator/super admin di sekolah yang diberi hak akses **Admin**.
- **Isi:** cara masuk, penjelasan setiap menu, dan langkah demi langkah untuk menambah, melihat, mengubah, serta menghapus data.
- **Istilah penting:**
  - **Tahun Ajaran (TA):** periode belajar, contoh `2026/2027`.
  - **Semester:** bagian dari tahun ajaran, yaitu **Ganjil** atau **Genap**.
  - **Rombel (Rombongan Belajar):** kelas, contoh `X-A`, `XI-1`, `VII-A`.
  - **Penempatan Siswa:** proses memasukkan siswa ke dalam kelas.
  - **Penugasan Guru:** proses menentukan guru pengampu beserta kelas dan mata pelajarannya.
  - **Waka Kurikulum:** Wakil Kepala Sekolah bidang Kurikulum.
  - **NPSN:** Nomor Pokok Sekolah Nasional.

> **Catatan teknis:** aplikasi dapat diakses dari komputer (browser seperti Chrome, Edge) maupun ponsel/hp (tampilan otomatis menyesuaikan).

---

## 2. Mengenal SinergiEdu dan Peran Admin

SinergiEdu adalah aplikasi manajemen sekolah yang digunakan bersama oleh berbagai pengguna (peran/role):

| Peran | Singkatan peran yang dilihat sistem | Contoh pengguna |
| --- | --- | --- |
| Admin | Admin | Operator/tata usaha (Anda) |
| Guru | Guru | Guru mata pelajaran & wali kelas |
| Siswa | Siswa | Peserta didik |
| Orang Tua/Wali | Orang Tua | Ayah/ibu/wali murid |
| Waka Kurikulum | Waka Kurikulum | Wakil kepala sekolah kurikulum |
| Pengawas | Pengawas | Pengawas sekolah dari dinas |
| Kepala Sekolah | Kepala Sekolah/Madrasah | Kepala sekolah/madrasah |
| Komite Sekolah | Komite Sekolah | Anggota komite sekolah |
| Super Admin | Super Admin | Administrator platform (menangani lintas sekolah) |

**Tugas utama Admin:**
1. Mengelola data akademik: tahun ajaran, semester, mata pelajaran, dan kelas.
2. Membuatkan akun login untuk: guru, siswa, orang tua, waka, pengawas, kepala sekolah, dan komite.
3. Menempatkan siswa ke kelas dan menugaskan guru mengajar.
4. Mengatur identitas sekolah (nama, logo, NPSN, kontak, alamat).

---

## 3. Cara Masuk (Login)

**Langkah 1 — Buka halaman login.**
Buka browser dan ketik alamat aplikasi, contoh `http://localhost` lalu `/login`, atau langsung `http://localhost/login`.

**Langkah 2 — Masukkan email dan kata sandi.**
1. Isi kolom **Alamat Email** dengan email akun Admin Anda.
2. Isi kolom **Kata Sandi** dengan password yang diberikan oleh Super Admin.
3. (Opsional) Centang **Ingat Saya** agar tidak perlu login ulang setiap membuka aplikasi.
4. Klik tombol **Masuk Sekarang**.

Alternatif: klik tombol **Lanjutkan dengan Google** bila akun sekolah sudah dihubungkan dengan Google Workspace.

> **Jika lupa kata sandi:** klik tautan **Lupa sandi?** di halaman login lalu ikuti petunjuknya. Jika tidak bisa, hubungi Super Admin platform.

**Petunjuk alamat halaman Admin:**
- Tampilan awal Admin: menu **Dashboard**.
- Semua menu Admin berciri alamat yang diawali `/admin` (contoh: `http://localhost/admin`).

> **Ke mana setelah login?** Sistem otomatis mengarahkan ke halaman sesuai peran Anda. Sebagai Admin, Anda akan diarahkan ke `http://localhost/admin` (Dashboard Admin).

---

## 4. Mengenal Halaman Dashboard

Dashboard adalah "halaman rumah" Admin. Buka melalui menu kiri **Dashboard** atau alamat `http://localhost/admin`.

**Bagian-bagian dashboard:**

1. **Banner "System Management Workspace"** — menampilkan **Tahun Ajaran** dan **Semester yang sedang aktif**. Pastikan hanya satu tahun ajaran dan satu semester yang aktif (lihat bagian 5 dan 6).
   - Tombol **Tindakan Cepat** untuk membuat data baru: Tambah Guru, Tambah Siswa, Tambah Orang Tua, Tambah Kelas Baru.

2. **Pengingat (Alert) otomatis** — kotak kuning/biru/hijau yang muncul jika ada hal yang perlu dibereskan, seperti:
   - **"Konteks Akademik Belum Diatur"** → tahun ajaran/semester aktif belum ditentukan → klik **Atur Sekarang**.
   - **"Siswa Belum Ditempatkan (n)"** → ada siswa yang belum punya kelas → klik **Tempatkan**.
   - **"Guru Belum Ditugaskan (n)"** → ada guru yang belum mendapat tugas mengajar → klik **Tugaskan**.

3. **Kartu statistik** — jumlah **Total Guru**, **Total Siswa**, **Orang Tua**, dan **Total Kelas** (beserta jenjang pendidikan kelas).

4. **Grafik:**
   - **Pertumbuhan Pengguna** — jumlah pengguna 6 bulan terakhir.
   - **Distribusi Pengguna** — komposisi pengguna per peran.

5. **Pengguna Terdaftar Terbaru** — daftar pengguna yang baru dibuat, lengkap dengan tombol **Lihat** dan **Edit**. Di layar kecil (hp) tampil sebagai daftar kartu.

> **Tips:** jika muncul pengingat kuning, segera atur tahun ajaran aktif terlebih dahulu (bagian 5). Banyak menu lain bergantung pada tahun ajaran/semester aktif.

---

## 5. Mengelola Tahun Ajaran

Menu kiri: **Akademik → Tahun Ajaran** (alamat `http://localhost/admin/academic-years`).

**Apa itu?** Tahun ajaran adalah periode belajar, contoh `2026/2027`. Data ini menjadi "kerangka" untuk semester, kelas, dan penugasan.

### 5.1 Menambah Tahun Ajaran Baru
1. Buka menu **Tahun Ajaran**.
2. Klik tombol **Tambah Tahun Ajaran**.
3. Isi kolom **Tahun Ajaran** dengan format tahun, contoh: `2026/2027`.
4. Centang **Jadikan Tahun Ajaran Aktif** jika tahun ini yang sedang berjalan.
   - Hanya **satu** tahun ajaran yang boleh aktif pada satu waktu; jika ada TA lain yang aktif, TA itu otomatis menjadi nonaktif.
5. Klik **Simpan Tahun Ajaran**.

### 5.2 Melihat, Mengubah, dan Menghapus
- **Lihat:** pada daftar Tahun Ajaran, klik ikon mata **Lihat Detail**. Halaman ini memperlihatkan semester di dalam TA tersebut.
- **Ubah:** klik **Edit** untuk mengganti nama/status aktif TA.
- **Hapus:** klik tombol **Hapus**, lalu setujui konfirmasi.
  - **Penting:** jika TA sedang dipakai oleh Kelas atau Semester, sistem **menolak penghapusan** demi keamanan data.

> **Aturan penting:** pastikan hanya satu tahun ajaran bertanda **Aktif**. Dashboard dan form Penugasan selalu memakai tahun ajaran/semester aktif.

---

## 6. Mengelola Semester

Menu kiri: **Akademik → Semester** (alamat `http://localhost/admin/semesters`).

**Apa itu?** Semester adalah pembagian dalam satu tahun ajaran: **Ganjil** atau **Genap**.

### 6.1 Menambah Semester
1. Buka menu **Semester**.
2. Klik **Tambah Semester**.
3. Pilih **Tahun Ajaran** pada kotak pilihan.
4. Pilih **Semester**: **Ganjil** atau **Genap**.
   - Sistem menolak jika semester dengan nama sama sudah ada di TA tersebut.
5. Centang **Jadikan Semester Aktif** bila semester tersebut yang sedang berjalan.
   - Hanya **satu** semester yang boleh aktif dalam satu TA.
6. Klik **Simpan Semester**.

### 6.2 Mengubah dan Menghapus
- **Ubah:** klik **Edit Semester** → perbaiki data → **Perbarui Semester**.
- **Hapus:** klik **Hapus** pada daftar.
- **Lihat:** klik **Lihat Detail** untuk melihat ringkasan semester tersebut.

---

## 7. Mengelola Mata Pelajaran

Menu kiri: **Akademik → Mata Pelajaran** (alamat `http://localhost/admin/subjects`).

**Apa itu?** Daftar mata pelajaran yang diajarkan di sekolah, misal: Matematika (kode `MAT`), Bahasa Indonesia, IPA, dan seterusnya.

### 7.1 Menambah Mata Pelajaran
1. Buka menu **Mata Pelajaran**.
2. Klik **Tambah Mata Pelajaran**.
3. Isi **Nama Mata Pelajaran** (contoh: `Matematika`).
4. Isi **Kode Mata Pelajaran** (contoh: `MAT`).
5. Klik **Simpan Mata Pelajaran**.

### 7.2 Mencari, Mengubah, dan Menghapus
- **Cari:** gunakan kotak pencarian "Cari nama atau kode mata pelajaran...".
- **Ubah:** klik **Edit** pada baris mata pelajaran.
- **Hapus:** klik tombol **Hapus** pada barisnya.

---

## 8. Mengelola Kelas (Rombel)

Menu kiri: **Akademik → Kelas** (alamat `http://localhost/admin/classes`).

**Apa itu?** Kelas/rombel adalah ruang belajar, contoh `X-A`, `XI-1`, `VII-B`. Setiap kelas terkait dengan jenjang, tingkat, dan tahun ajaran.

### 8.1 Menambah Kelas Baru
1. Buka menu **Kelas**.
2. Klik **Tambah Kelas**.
3. Isi data berikut:
   - **Jenjang Pendidikan** (dipilih otomatis sesuai pilihan): contoh SD, SMP, SMA/SMK/MA.
   - **Tingkat Kelas** (disesuaikan jenjang): contoh untuk SMA: Kelas 10, 11, 12.
   - **Nama Kelas / Rombel**: contoh `X-A`. (Sistem menyarankan nama sesuai jenjang/tingkat.)
   - **Tahun Ajaran**: pilih tahun ajaran kelas ini berlaku.
   - **Wali Kelas** (opsional): pilih guru yang menjadi wali kelas. Bisa dikosongkan dengan **-- Belum Ditentukan --**.
4. Klik **Simpan Data Kelas**.

### 8.2 Melihat, Mengubah, dan Menghapus
- **Lihat:** klik **Lihat Detail** untuk melihat daftar siswa dan wali kelas.
- **Ubah:** klik **Edit** → **Perbarui Data Kelas**.
- **Hapus:** klik tombol **Hapus** pada baris kelas (ada konfirmasi sebelum dihapus).

> **Tips.** Buat kelas setelah tahun ajaran dan semester aktif ditentukan. Data kelas biasanya dikunci/diadakan per tahun ajaran.

---

## 9. Mengelola Guru

Menu kiri: **Pengguna → Guru** (alamat `http://localhost/admin/teachers`).

**Apa itu?** Halaman ini mengelola data guru sekaligus **membuatkan akun login** untuk guru.

### 9.1 Menambah Guru Baru
1. Buka menu **Guru**.
2. Klik **Tambah Guru**.
3. Isi **Informasi Akun:**
   - **Nama Guru** (nama lengkap).
   - **Email Guru (Akses Login)** — email inilah yang dipakai guru untuk masuk sistem.
   - **Password Sementara** — dibuat otomatis. Cukup klik tombol **Salin** untuk menyalinnya lalu berikan kepada guru.
4. Isi **Profil Guru** (boleh dikosongkan):
   - **NIP** (Nomor Induk Pegawai).
   - **Nomor HP**.
   - **Alamat**.
5. Klik **Simpan Data Guru**.

> **Setelah guru dibuat**, lanjutkan dengan **Penugasan Guru** (bagian 17) agar guru tahu kelas dan mata pelajaran yang diampu. Guru juga bisa dijadikan **Wali Kelas** saat membuat/mengubah kelas.

### 9.2 Mencari, Melihat, Mengubah, dan Menghapus
- **Cari:** gunakan kotak pencarian nama guru / NIP / email.
- **Lihat:** klik **Lihat Detail** — halaman ini menampilkan profil guru serta **daftar penugasan mengajarnya**; dari sini Anda bisa langsung **Tambah Penugasan** untuk guru itu.
- **Ubah:** klik **Edit** → perbaiki data → **Perbarui**.
- **Hapus:** klik tombol **Hapus** pada baris guru (ada konfirmasi).

---

## 10. Mengelola Siswa

Menu kiri: **Pengguna → Siswa** (alamat `http://localhost/admin/students`).

**Apa itu?** Halaman untuk mendaftarkan siswa sekaligus membuatkan akun login siswa.

### 10.1 Menambah Siswa Baru
1. Buka menu **Siswa**.
2. Klik **Tambah Siswa**.
3. Isi **Informasi Akun:**
   - **Nama Siswa**.
   - **Email Siswa (Akses Login)**.
   - **Password Sementara** (otomatis; bisa disalin).
4. Isi **Profil & Akademik:**
   - **NIS** (Nomor Induk Siswa).
   - **Jenis Kelamin**: Laki-laki / Perempuan.
   - **Tanggal Lahir**.
   - **Orang Tua / Wali** (opsional): pilih wali dari daftar. Bisa dikosongkan dan dihubungkan nanti dari halaman Orang Tua/Wali.
5. Klik **Simpan Data Siswa**.

> **Setelah siswa dibuat**, siswa belum otomatis masuk kelas. Lanjutkan ke **Penempatan Siswa** (bagian 16).

### 10.2 Melihat, Mengubah, dan Menghapus
- **Lihat:** klik **Lihat Detail** untuk melihat profil, kelas saat ini, dan riwayat penempatan.
- **Ubah:** klik **Edit** → perbarui → simpan.
- **Hapus:** klik **Hapus** pada baris siswa (dengan konfirmasi).

---

## 11. Mengelola Orang Tua / Wali

Menu kiri: **Pengguna → Orang Tua/Wali** (alamat `http://localhost/admin/parents`).

**Apa itu?** Mendaftarkan wali murid, membuat akun login wali, dan **menghubungkan wali dengan anak (siswa)** agar wali bisa memantau nilai dan perkembangan anaknya.

### 11.1 Menambah Orang Tua / Wali Baru
1. Buka menu **Orang Tua/Wali**.
2. Klik **Tambah Orang Tua/Wali**.
3. Isi **Informasi Akun:**
   - **Nama Wali**.
   - **Email Wali (Akses Login)**.
   - **Password Sementara** (otomatis; bisa disalin).
4. Isi **Profil Orang Tua** (opsional): **Nomor HP** dan **Alamat Rumah**.
5. Pada bagian **Hubungkan dengan Siswa (Anak):**
   - Cari anak di panel kiri (ketik **nama** atau **NIS**).
   - Klik **Tambahkan** pada siswa yang cocok; siswa masuk ke panel **Siswa yang dipilih** di kanan.
   - Untuk membatalkan pilihan, klik tombol hapus pada kartu siswa di kanan.
6. Klik **Simpan Data Orang Tua**.

> **Tips:** bila Anda lupa menghubungkan anak saat membuat wali, gunakan **Edit** pada data wali yang sama — bagian penghubungan siswa tersedia di sana juga.

### 11.2 Melihat, Mengubah, dan Menghapus
- **Lihat:** klik **Lihat Detail** — menampilkan profil wali dan **daftar anak yang dihubungkan**.
- **Ubah:** klik **Edit** (termasuk untuk memperbarui daftar anak).
- **Hapus:** klik **Hapus** pada baris wali (ada konfirmasi).

---

## 12. Mengelola Waka Kurikulum

Menu kiri: **Pengguna → Waka Kurikulum** (alamat `http://localhost/admin/wakas`).

**Apa itu?** Mendaftarkan **Wakil Kepala Sekolah bidang Kurikulum** beserta akun loginnya.

### 12.1 Menambah Waka Kurikulum Baru
1. Buka menu **Waka Kurikulum**.
2. Klik **Tambah Waka Kurikulum Baru**.
3. Isi **Informasi Akun:**
   - **Nama Waka**.
   - **Email Waka (Akses Login)**.
   - **Password Sementara** (otomatis; bisa disalin).
4. Isi **Profil Waka** (opsional): **NIP**, **Nomor HP**, **Alamat**.
5. Klik **Simpan Waka Kurikulum**.

### 12.2 Melihat, Mengubah, dan Menghapus
- **Lihat:** klik **Lihat Detail**.
- **Ubah:** klik **Edit** → **Perbarui**.
- **Hapus:** klik **Hapus** pada baris waka (dengan konfirmasi).

---

## 13. Mengelola Pengawas

Menu kiri: **Pengguna → Pengawas** (alamat `http://localhost/admin/pengawas`).

**Apa itu?** Pengawas adalah pejabat dari dinas yang **membina dan mengawasi sekolah**. Ada dua cara menautkan pengawas ke sekolah Anda.

### 13.1 Cara A — Tambah Pengawas Baru (dibuatkan akun)
1. Buka menu **Pengawas**.
2. Klik **Tambah Pengawas Baru**.
3. Isi **Informasi Akun:**
   - **Nama Pengawas**.
   - **Email Pengawas (Akses Login)**.
   - **Password Sementara** (otomatis; bisa disalin).
4. Isi **Profil Pengawas** (opsional): **NIP**, **Nomor HP**, **Alamat**.
5. Pada bagian **Sekolah yang Diawasi:**
   - Centang **minimal satu sekolah** yang akan diawasi pengawas ini.
   - Pengawas **hanya bisa mengakses data sekolah yang dicentang**.
6. Klik **Simpan Pengawas**.

### 13.2 Cara B — Hubungkan Pengawas yang Sudah Ada
1. Buka menu **Pengawas**.
2. Klik **Hubungkan Pengawas** (atau alamat `http://localhost/admin/pengawas/connect`).
3. Gunakan kotak pencarian: cari nama, NIP, atau email pengawas (contoh: `Cari nama, NIP, atau email Pengawas...`).
4. Pada baris pengawas yang ditemukan, jika statusnya **Belum Terhubung**, klik **Hubungkan Pengawas**.
   - Jika sudah **Terhubung**, tombol otomatis menjadi **Sudah Terhubung** (tidak bisa diklik ganda).

### 13.3 Melihat, Mengubah, dan Memutus Hubungan
- **Lihat:** klik **Lihat Detail** pada daftar pengawas.
- **Ubah:** klik **Edit** → perbaiki data → **Perbarui**. Di halaman edit, Anda juga bisa mengubah daftar **sekolah yang diawasi**.
- **Putus hubungan:** gunakan aksi pemutusan di daftar pengawas bila fitur ditampilkan (tidak menghapus akun pengawas dari platform).
- **Hapus:** klik **Hapus** untuk menghapus data dari daftar (dengan konfirmasi).

---

## 14. Mengelola Kepala Sekolah / Madrasah

Menu kiri: **Pengguna → Kepala Sekolah** (alamat `http://localhost/admin/kepala-sekolah`).

**Apa itu?** Mendaftarkan Kepala Sekolah/Madrasah beserta akun loginnya.

### 14.1 Menambah Kepala Sekolah / Madrasah
1. Buka menu **Kepala Sekolah**.
2. Klik **Tambah Kepala Sekolah/Madrasah**.
3. Isi **Informasi Akun:**
   - **Nama Kepala Sekolah/Madrasah**.
   - **Email (Akses Login)**.
   - **Password Sementara** (otomatis; bisa disalin).
4. Isi **Profil** (opsional): **NIP**, **Nomor HP**, **Alamat**.
5. Klik **Simpan Data**.

### 14.2 Melihat, Mengubah, dan Menghapus
- **Lihat:** klik **Lihat Detail**.
- **Ubah:** klik **Edit** → perbaiki → simpan.
- **Hapus:** klik **Hapus** pada baris (dengan konfirmasi).

---

## 15. Mengelola Komite Sekolah

Menu kiri: **Pengguna → Komite Sekolah** (alamat `http://localhost/admin/komite`).

**Apa itu?** Mendaftarkan pengurus/anggota **Komite Sekolah** beserta akun loginnya.

### 15.1 Menambah Komite Sekolah
1. Buka menu **Komite Sekolah**.
2. Klik **Tambah Komite Sekolah**.
3. Isi:
   - **Nama Lengkap**.
   - **Email**.
   - **Password** dan **Konfirmasi Password**.
   - **Status Akun**: pilih **Aktif** (bisa login) atau **Nonaktif**.
4. Klik **Simpan Komite**.

### 15.2 Melihat, Mengubah, dan Menonaktifkan
- **Lihat:** klik **Lihat Detail**.
- **Ubah:** klik **Edit** → perbarui data/status → simpan.
- **Hapus:** klik **Hapus** pada baris (dengan konfirmasi).

---

## 16. Penempatan Siswa ke Kelas

Menu kiri: **Penugasan → Penempatan Siswa** (alamat `http://localhost/admin/student-placements/create`).

**Apa itu?** Memasukkan **satu atau banyak siswa sekaligus** ke dalam kelas pada tahun ajaran aktif. Hanya siswa yang **belum memiliki kelas** yang muncul — jadi menempatkan lebih dari sekali aman dilakukan.

### 16.1 Langkah Menempatkan Siswa
1. Buka menu **Penempatan Siswa**.
2. Pada **Target Kelas**, pilih kelas tujuan (muncul hanya kelas pada tahun ajaran aktif).
   - Jika belum ada kelas, muncul pesan peringatan dengan tautan **Buat Kelas Baru** — klik untuk membuat kelas lebih dulu, lalu kembali lagi.
3. Cari siswa pada kotak **"Cari Nama / NIS..."** bila daftarnya panjang.
4. Centang kotak siswa yang akan ditempatkan; gunakan kotak centang di baris paling atas untuk memilih **semua siswa** yang tampil.
5. Periksa kolom bawah: **"x siswa akan ditempatkan di kelas tujuan."**
6. Klik **Tempatkan Siswa (x)** (*ganti x sesuai jumlah terpilih*).

> **Memindahkan siswa antar kelas:** dari halaman **Daftar Penempatan** (`/admin/student-placements`), gunakan aksi **Edit** ("Pindah Kelas") pada penempatan yang bersangkutan.

### 16.2 Melihat dan Menghapus Penempatan
- **Lihat:** menu **Penempatan Siswa** menampilkan daftar siswa berikut kelasnya dalam satu tabel (desktop) / kartu (hp).
- **Hapus/lepaskan:** gunakan tombol hapus pada penempatan yang salah (dengan konfirmasi) — umumnya dari halaman **Lihat Detail Siswa**.

---

## 17. Penugasan Guru (Guru Mengampu)

Menu kiri: **Penugasan → Penugasan Guru** (alamat `http://localhost/admin/teacher-assignments/create`).

**Apa itu?** Menentukan **guru pengampu** untuk setiap pasangan **kelas + mata pelajaran** pada semester aktif. Satu guru dapat diberi **banyak penugasan sekaligus**.

### 17.1 Langkah Menugaskan Guru
1. Buka menu **Penugasan Guru**.
2. **Guru Pengampu:** pilih nama guru. (Label mengikuti format `Nama Guru (NIP. ...)`.)
   - Jika datang dari halaman **Lihat Detail Guru**, guru sudah otomatis terpilih.
3. Periksa **Tahun Ajaran Aktif** dan **Semester Aktif** (tampil sebagai informasi; tidak bisa diubah di form ini karena selalu mengikuti tahun ajaran/semester aktif).
4. Di bagian **Rincian Penugasan Mengajar** terdapat satu baris awal:
   - **Kelas** — pilih kelas yang diampu.
   - **Mata Pelajaran** — pilih mapel yang diampu.
5. Untuk menambah pasangan kelas/mapel lain, klik **+ Tambah Penugasan** lalu isi kembali kelas dan mapelnya.
   - Untuk membuang satu baris, klik ikon **hapus** pada baris tersebut (baris terakhir tidak bisa dihapus agar minimal satu baris tersedia).
6. Klik **Simpan Penugasan**.

### 17.2 Mengubah dan Menghapus Penugasan
- **Ubah:** buka **Daftar Penugasan** (`/admin/teacher-assignments`) lalu klik **Edit** pada penugasan; atau dari **Lihat Detail Guru** → tab penugasan → **Edit**.
- **Hapus:** dari daftar penugasan klik tombol **Hapus** (dengan konfirmasi).

> **Catatan:** penugasan berlaku pada **tahun ajaran dan semester aktif**. Saat semester/TA berubah menjadi aktif, penugasan baru dibuat untuk periode yang sedang berjalan.

---

## 18. Pengaturan Sistem (Profil Sekolah)

Menu kiri: **Sistem → Pengaturan Sistem** (alamat `http://localhost/admin/settings`).

**Apa itu?** Mengatur **identitas sekolah/madrasah** yang dipakai pada laporan dan tampilan sistem.

### 18.1 Mengubah Identitas Sekolah
1. Buka menu **Pengaturan Sistem**.
2. Bagian **Profil Sekolah / Madrasah:**
   - **Logo Sekolah/Madrasah:** klik **Pilih File Logo**. Format **JPG, PNG, SVG**, maksimal **2 MB**. Pratinjau logo tampil di kiri.
   - **Nama Sekolah/Madrasah** (wajib diisi) — contoh `SMA Negeri 1 Sinergi`.
   - **NPSN** — Nomor Pokok Sekolah Nasional.
   - **Nomor Telepon** — contoh `(021) 1234567`.
   - **Email Sekolah** — contoh `info@sekolah.sch.id`.
   - **Alamat Lengkap**.
3. Bagian **Informasi Sistem** menampilkan nama aplikasi dan versi, bersifat **hanya baca** (tidak bisa diubah).
4. Klik **Simpan Perubahan**.
   - Saat menunggu proses, tombol berubah menjadi **"Menyimpan..."**.

> **Tips:** gunakan logo berformat PNG dengan latar transparan agar hasil tampilan/laporan lebih rapi.

---

## 19. Tips, Aturan, dan Pertanyaan Umum

### Ringkasan alur pengaturan sekolah baru (disarankan)
1. **Tahun Ajaran** → buat tahun ajaran saat ini dan tandai **Aktif**.
2. **Semester** → buat dan tandai semester yang berjalan (Ganjil/Genap).
3. **Mata Pelajaran** → daftarkan semua mapel yang diajarkan.
4. **Kelas** → buat rombel beserta wali kelasnya.
5. **Guru** → buat akun guru.
6. **Siswa** → buat akun siswa (dan NIS).
7. **Orang Tua/Wali** → buat akun wali lalu hubungkan dengan anak.
8. **Penempatan Siswa** → tempatkan siswa ke kelas.
9. **Penugasan Guru** → tugaskan guru mengampu kelas+mapel.
10. **Pengawas / Waka / Kepala Sekolah / Komite** → buat/hubungkan akun pengguna lainnya.
11. **Pengaturan Sistem** → lengkapi identitas sekolah.

### Pertanyaan Umum

**Q: Mengapa formulir penugasan "minta diisi lagi" bahkan padahal sudah mengisi?**
A: Pastikan tahun ajaran *dan* semester aktif sudah ditentukan (bagian 5–6). Formulir penugasan & penempatan mengikuti konteks akademik yang aktif.

**Q: Saya tidak melihat siswa di menu Penempatan Siswa.**
A: Itu tanda seluruh siswa sudah memiliki kelas pada tahun ajaran aktif. Menu hanya menampilkan siswa yang *belum* ditempatkan.

**Q: Satu siswa bisa punya dua orang tua/wali?**
A: Bisa. Pada halaman **Orang Tua/Wali**, hubungkan siswa yang sama ke wali lainnya. Sebaliknya, dari **Edit Data Siswa** Anda bisa memilih atau mengosongkan **Orang Tua / Wali** pada kolom yang tersedia.

**Q: Apakah menghapus Tahun Ajaran berbahaya?**
A: Aman, karena sistem **menolak penghapusan** bila TA masih dipakai oleh kelas/semester. Penghapusan hanya bisa dilakukan jika TA benar-benar "kosong".

**Q: Password sementara bisa diubah lagi?**
A: Ya. Saat membuat akun, password dibuat otomatis (bisa disalin). Admin dapat mengubahnya lewat **Edit** pada data pengguna, atau pengguna itu sendiri mengganti lewat menu profilnya.

**Q: Saya login sebagai Admin tetapi tidak masuk ke dashboard Admin.**
A: Pastikan akun yang dipakai benar-benar berperan **Admin** (bukan Guru/Siswa, dsb.), dan status akun aktif. Sistem mengarahkan halaman sesuai peran. Hubungi Super Admin bila ragu.

**Q: Bolehkah satu akun login dipakai berbagian?**
A: Sebaiknya tidak. Setiap orang sebaiknya memakai akunnya sendiri agar jejak data dan keamanan terjaga.

### Aturan penting yang selalu berlaku
- **Satu** tahun ajaran aktif dan **satu** semester aktif pada satu waktu.
- Data yang masih dipakai entitas lain **tidak bisa dihapus** (perlindungan data).
- Hanya akun berperan **Admin/Super Admin** yang bisa membuka halaman `/admin`.

---

*Panduan ini disusun berdasarkan perilaku aplikasi SinergiEdu versi saat ini. Apabila tampilan menu berubah seiring pembaruan, prinsip langkahnya tetap sama.*

*Dokumen lanjutan yang direncanakan: buku panduan untuk peran Guru, Siswa, Orang Tua/Wali, Waka Kurikulum, Pengawas, Kepala Sekolah, dan Komite Sekolah.*