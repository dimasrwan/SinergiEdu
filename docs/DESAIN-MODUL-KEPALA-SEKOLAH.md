# DOKUMEN RANCANGAN MODUL KEPALA SEKOLAH / MADRASAH
## Sistem Informasi Manajemen Sekolah — SinergiEdu

---

| Field | Value |
|-------|-------|
| **Modul** | Kepala Sekolah / Madrasah |
| **Kategori** | Dashboard Eksekutif & Manajemen Kolaborasi |
| **Laravel Version** | ^13.8 |
| **PHP Version** | ^8.3 |
| **Stack** | Laravel Breeze, Tailwind CSS, Alpine.js, Chart.js |
| **Multi-Tenant** | Row-level `school_id` via TenantScope |
| **Tanggal Dokumen** | 1 September 2026 |
| **Status** | Rancangan Awal (Draft) |

---

## DAFTAR ISI

1. [Konteks & Analisis Kesenjangan](#1-konteks--analisis-kesenjangan)
2. [Hak Akses & Batasan User (Permissions)](#2-hak-akses--batasan-user-permissions)
3. [Fitur Utama Modul Kepala Sekolah](#3-fitur-utama-modul-kepala-sekolah)
4. [Alur Kerja User (User Journey / Flow)](#4-alur-kerja-user-user-journey--flow)
5. [Rancangan Tampilan / UI Wireframe (Konseptual)](#5-rancangan-tampilan--ui-wireframe-konseptual)
6. [Spesifikasi Teknis Implementasi](#6-spesifikasi-teknis-implementasi)
7. [Skema Data & Relasi](#7-skema-data--relasi)
8. [Rekomendasi Pengembangan](#8-rekomendasi-pengembangan)

---

## 1. KONTEKS & ANALISIS KESENJANGAN

### 1.1 Posisi Kepala Sekolah dalam Ekosistem

Kepala Sekolah/Madrasah merupakan **level manajerial tertinggi** di tingkat sekolah. Perannya bersifat **supervisory & executive** — tidak terlibat langsung dalam operasional harian pengajaran, namun bertanggung jawab atas:

- **Monitoring agregat** performa akademik seluruh sekolah
- **Supervisi kinerja guru** (kinerja pengajaran, ketepatan input nilai)
- **Evaluasi perkembangan karakter & hafalan siswa** pada level makro
- **Pemberian feedback strategis** & rencana aksi kepada Waka, Guru, dan Pengawas
- **Persetujuan & distribusi laporan** periodik (mingguan/bulanan/semester)

### 1.2 Status Implementasi Saat Ini

| Komponen | Status | Keterangan |
|----------|--------|------------|
| Model `KepalaSekolah` | ✅ Selesai | `user_id`, `nip`, `phone`, `address` |
| Role `kepala_sekolah` | ✅ Seeded | Tersedia di tabel `roles` |
| Migration `kepala_sekolahs` | ✅ Selesai | Tabel profile Kepala Sekolah |
| `KepalaSekolahPolicy` | ✅ Selesai | View/update own profile |
| Admin CRUD Kepala Sekolah | ✅ Selesai | `Admin\KepalaSekolahController` |
| **Route group `/kepala-sekolah`** | ❌ Belum ada | Tidak ada route khusus role |
| **Sidebar `kepala-sekolah`** | ❌ Belum ada | Tidak ada komponen sidebar |
| **Dashboard Kepala Sekolah** | ❌ Belum ada | Tidak ada view dashboard |
| **Login redirect** | ❌ Belum ada | Role `kepala_sekolah` fallthrough ke default |
| **Controller Kepala Sekolah** | ❌ Belum ada | Tidak ada controller role-facing |
| **Export Laporan** | ❌ Belum ada | Tidak ada fitur export |

### 1.3 Kesenjangan Kritis

1. **Tidak ada entry point** — Kepala Sekolah yang login tidak memiliki dashboard atau navigasi
2. **Tidak ada data aggregation layer** — Tidak ada service/controller yang mengagregasi data lintas model untuk level manajerial
3. **Tidak ada mekanisme feedback top-down** — Feedback dalam sistem saat ini hanya dari Guru → Siswa
4. **Tidak ada fitur export** — Tidak ada laporan PDF/Excel yang bisa diunduh

---

## 2. HAK AKSES & BATASAN USER (PERMISSIONS)

### 2.1 Prinsip Desain Permissions

```
┌─────────────────────────────────────────────────────────┐
│  PRINSIP: Kepala Sekolah memiliki akses READ-HEAVY      │
│  dengan akses WRITE terbatas pada:                       │
│  • Feedback strategis (kepada Guru/Waka/Pengawas)       │
│  • Rencana aksi                                           │
│  • Persetujuan laporan                                    │
│  • Profil diri sendiri                                    │
│                                                         │
│  Kepala Sekolah TIDAK dapat:                             │
│  • Input/edit nilai siswa langsung                       │
│  • CRUD mata pelajaran/kelas/tahun ajaran                │
│  • Menghapus data apapun                                 │
│  • Mengakses data di luar sekolahnya (tenant isolation)  │
└─────────────────────────────────────────────────────────┘
```

### 2.2 Matrix Hak Akses Detail

#### A. DATA YANG DAPAT DILIHAT (READ)

| No | Entitas Data | Scope | Keterangan |
|----|-------------|-------|------------|
| 1 | Dashboard Rekapitulasi Akademik | **Aggregat sekolah** | Rata-rata nilai per kelas, per mata pelajaran, per komponen (pre_test, assignment, post_test, character, memorization) |
| 2 | Tren Perkembangan Nilai Siswa | **Semua siswa sekolah** | Chart tren per kelas, per mata pelajaran, per semester |
| 3 | Data Siswa (identitas) | **Semua siswa sekolah** | Nama, NISN, NIS, kelas aktif, rata-rata nilai |
| 4 | Data Guru (identitas) | **Semua guru sekolah** | Nama, NIP, mata pelajaran yang diampu, jumlah kelas |
| 5 | Data Kelas | **Semua kelas sekolah** | Komposisi kelas, wali kelas, jumlah siswa |
| 6 | Rata-rata Nilai per Kelas | **Semua kelas** | Rata-rata per komponen per kelas |
| 7 | Rata-rata Nilai per Mata Pelajaran | **Semua mata pelajaran** | Rata-rata komponen per mapel, distribusi di atas/bawah KKM |
| 8 | Status Penilaian Guru | **Semua guru** | Guru yang sudah/s belum input nilai, ketepatan waktu |
| 9 | Rekap Kehadiran Siswa | **Semua siswa** (aggregat) | Persentase kehadiran per kelas, tren kehadiran |
| 10 | Data Hafalan & Karakter Siswa | **Semua siswa** (aggregat) | Rata-rata `character_score` dan `memorization_score` per kelas/mapel |
| 11 | Laporan Kinerja Guru | **Semua guru** | Rekap pengajaran, ketepatan input, jumlah materi/tugas |
| 12 | Feedback dari Guru ke Siswa | **Semua feedback** (read-only) | Aggregat feedback positif/negatif/netral |
| 13 | Evaluasi Sekolah (oleh Pengawas) | **Evaluasi yang ditujukan untuk sekolah** | Dokumen evaluasi dari Pengawas |
| 14 | Rencana Aksi | **Rencana aksi sendiri** | Rencana aksi yang dibuat oleh Kepala Sekolah |
| 15 | Laporan Rekap Mingguan/Bulanan | **Aggregat sekolah** | Data teragregasi untuk periode tertentu |

#### B. DATA YANG DAPAT DIIISI / DIEDIT (WRITE)

| No | Aksi | Entitas | Keterangan |
|----|------|---------|------------|
| 1 | **Buat** | Feedback Strategis | Feedback kepada Guru/Waka/Pengawas (type: strategis) |
| 2 | **Buat** | Rencana Aksi | Rencana aksi berdasarkan data monitoring |
| 3 | **Buat** | Catatan Evaluasi | Evaluasi kinerja guru per periode |
| 4 | **Edit** | Profil Sendiri | Nama, email, NIP, telepon, alamat |
| 5 | **Setujui** | Laporan Rekap | Menandai laporan sebagai "disetujui" |
| 6 | **Buat** | School Evaluation | Evaluasi sekolah (menggunakan `SchoolEvaluation` model) |

#### C. DATA YANG DAPAT DIUNDUH (EXPORT)

| No | Format | Konten | Parameter |
|----|--------|--------|-----------|
| 1 | PDF | Rekapitulasi Nilai Semester | Per kelas, per mapel, per komponen |
| 2 | Excel | Rekapitulasi Nilai Semester | Detail per siswa per mapel |
| 3 | PDF | Laporan Kinerja Guru | Per guru, per periode |
| 4 | Excel | Laporan Kehadiran Siswa | Per kelas, per periode |
| 5 | PDF | Laporan Karakter & Hafalan | Per kelas, per siswa |
| 6 | PDF | Laporan Rekap Mingguan | Aggregat mingguan |
| 7 | Excel | Master Data Siswa | Identitas + rekap nilai |
| 8 | PDF | Evaluasi Sekolah | Dokumen evaluasi |

#### D. DATA YANG TIDAK DAPAT DIAKSES (RESTRICTED)

| No | Entitas | Alasan Pembatasan |
|----|---------|-------------------|
| 1 | Input/Edit Nilai Siswa | Tanggung jawab Guru, bukan level manajerial |
| 2 | CRUD Mata Pelajaran | Tanggung jawab Admin/Waka Kurikulum |
| 3 | CRUD Kelas | Tanggung jawab Admin |
| 4 | CRUD Tahun Ajaran/Semester | Tanggung jawab Admin |
| 5 | Hapus Data | Mencegah kehilangan data kritis |
| 6 | Manajemen User/Role | Tanggung jawab Super Admin/Admin |
| 7 | Konfigurasi Sistem/Setting | Tanggung jawab Admin |

### 2.3 Aturan Tenant Isolation

```
SEMUA query yang dijalankan oleh Kepala Sekolah WAJIB di-scoped
oleh school_id miliknya sendiri.

Alur verifikasi:
1. KepalaSekolah.user_id → User.school_id → school_id
2. TenantScope pada semua model otomatis filter by school_id
3. KepalaSekolahPolicy.before() memastikan akses hanya dalam scope sekolah sendiri
```

---

## 3. FITUR UTAMA MODUL KEPALA SEKOLAH

### 3.1 Sidebar Navigation Structure

```
📁 Kepala Sekolah Sidebar
├── 🏠 Dashboard Eksekutif
├── 📊 Monitoring Akademik
│   ├── Rekapitulasi Nilai
│   ├── Perkembangan Siswa
│   └── Analitik Mata Pelajaran
├── 👨‍🏫 Supervisi Kinerja Guru
│   ├── Status Penilaian
│   └── Laporan Kinerja Guru
├── 📝 Evaluasi & Feedback
│   ├── Feedback Strategis
│   ├── Rencana Aksi
│   └── Evaluasi Sekolah
├── 📄 Laporan
│   ├── Rekap Mingguan
│   ├── Rekap Bulanan
│   └── Rekap Semester
└── ⚙️ Pengaturan
    └── Profil Saya
```

### 3.2 Fitur Detail per Halaman

---

#### FITUR 1: Dashboard Eksekutif

**Route:** `GET /kepala-sekolah/dashboard`
**Controller:** `KepalaSekolah\DashboardController@index`
**View:** `resources/views/pages/kepala-sekolah/dashboard.blade.php`

**Deskripsi:** Halaman utama yang menampilkan ringkasan eksekutif performa sekolah secara real-time.

**Komponen Widget:**

| Widget | Tipe | Data Source | Keterangan |
|--------|------|-------------|------------|
| School Health Index | Stat Card (Banner) | Aggregat `student_grades` | Rata-rata nilai seluruh sekolah + delta dari periode sebelumnya |
| Total Guru Aktif | Stat Card | `teachers.count()` WHERE school_id | Jumlah guru aktif |
| Total Siswa Aktif | Stat Card | `students.count()` WHERE school_id | Jumlah siswa aktif |
| Total Kelas | Stat Card | `classes.count()` WHERE school_id | Jumlah kelas aktif |
| Rata-rata Kehadiran | Stat Card | `attendances` (aggregat) | Persentase kehadiran rata-rata |
| Chart Tren Nilai | Line Chart | `student_grades` grouped by month | Tren rata-rata nilai 6 bulan terakhir |
| Peringkat Kelas | Ranked Table | `student_grades` grouped by class | Peringkat berdasarkan rata-rata nilai |
| Komponen Nilai Sekolah | Bar Chart | `student_grades` avg per komponen | Pre-test, Assignment, Post-test, Character, Memorization |
| Intervensi Diperlukan | Alert List | Logic (guru telat input, kelas turun drastis) | Siswa/kelas yang memerlukan perhatian |
| Laporan Cepat | Quick Links | Static routes | Shortcut ke halaman rekap/laporan |

**Query Logic (pseudocode):**
```
// School Average Grade
SELECT AVG(pre_test_score + assignment_score + post_test_score + character_score + memorization_score) / 5
FROM student_grades sg
JOIN users u ON sg.student_id = u.id
WHERE u.school_id = {current_school_id}
AND sg.semester_id = {active_semester_id}

// Class Rankings
SELECT c.name, AVG(sg.average_score) as avg
FROM student_grades sg
JOIN classes c ON sg.class_id = c.id
WHERE c.school_id = {current_school_id}
AND sg.semester_id = {active_semester_id}
GROUP BY c.id
ORDER BY avg DESC

// Component Averages
SELECT
  AVG(pre_test_score) as avg_pre_test,
  AVG(assignment_score) as avg_assignment,
  AVG(post_test_score) as avg_post_test,
  AVG(character_score) as avg_character,
  AVG(memorization_score) as avg_memorization
FROM student_grades sg
WHERE sg.school_id = {current_school_id}
AND sg.semester_id = {active_semester_id}
```

---

#### FITUR 2: Rekapitulasi Nilai

**Route:** `GET /kepala-sekolah/akademik/rekap`
**Controller:** `KepalaSekolah\AcademicController@rekap`
**View:** `resources/views/pages/kepala-sekolah/academic/rekap.blade.php`

**Deskripsi:** Halaman detail rekapitulasi nilai seluruh siswa dengan filter dan kemampuan export.

**Komponen:**

| Komponen | Tipe | Keterangan |
|----------|------|------------|
| Filter Panel | Form (dropdown) | Filter: Tahun Ajaran, Semester, Kelas, Mata Pelajaran |
| Tabel Rekap Nilai | Data Table | Kolom: No, Nama Siswa, NISN, Kelas, Pre-test, Tugas, Post-test, Karakter, Hafalan, Rata-rata, Status |
| Status Badge | Badge | Lulus (>=KKM) / Perlu Perhatian (<KKM) |
| Detail Drill-down | Link/Accordion | Klik nama siswa →展开 detail per mapel |
| Tombol Export PDF | Button | Export rekap filtered ke PDF |
| Tombol Export Excel | Button | Export rekap filtered ke Excel |

**Kolom Tabel Rekap:**

```
| No | Nama Siswa | NISN | Kelas | Pre-test | Tugas | Post-test | Karakter | Hafalan | Rata-rata | Status |
```

**Filter Scope:**
- Tahun Ajaran: Dropdown dari `academic_years` WHERE `school_id`
- Semester: Dropdown dari `semesters` WHERE `academic_year_id`
- Kelas: Dropdown dari `classes` WHERE `school_id` + `academic_year_id`
- Mata Pelajaran: Dropdown dari `subjects` WHERE `school_id`

---

#### FITUR 3: Perkembangan Siswa

**Route:** `GET /kepala-sekolah/akademik/perkembangan`
**Controller:** `KepalaSekolah\AcademicController@perkembangan`
**View:** `resources/views/pages/kepala-sekolah/academic/perkembangan.blade.php`

**Deskripsi:** Monitoring tren perkembangan siswa dari waktu ke waktu, termasuk aspek karakter dan hafalan.

**Komponen:**

| Komponen | Tipe | Keterangan |
|----------|------|------------|
| Filter Panel | Form | Filter: Kelas, Siswa, Periode |
| Chart Tren Nilai per Siswa | Line Chart | Tren 5 komponen nilai per siswa per bulan |
| Tabel Perkembangan | Data Table | Nama, Pre-test Δ, Tugas Δ, Post-test Δ, Karakter Δ, Hafalan Δ |
| Distribusi Karakter | Bar Chart | Sebaran `character_score` di seluruh siswa |
| Distribusi Hafalan | Bar Chart | Sebaran `memorization_score` di seluruh siswa |
| Siswa Berprestasi | Highlight Card | Top 10 siswa dengan rata-rata tertinggi |
| Siswa Perlu Perhatian | Alert Card | Siswa dengan penurunan >10% dari periode sebelumnya |

**Data Model untuk Karakter & Hafalan:**
```
// Sumber data dari student_grades:
// - character_score (integer 0-100)
// - memorization_score (integer 0-100)
//
// Agregasi per kelas:
SELECT
  c.name as class_name,
  AVG(sg.character_score) as avg_character,
  AVG(sg.memorization_score) as avg_memorization,
  COUNT(CASE WHEN sg.character_score < 70 THEN 1 END) as character_below_target,
  COUNT(CASE WHEN sg.memorization_score < 70 THEN 1 END) as memorization_below_target
FROM student_grades sg
JOIN classes c ON sg.class_id = c.id
WHERE c.school_id = {school_id}
AND sg.semester_id = {active_semester_id}
GROUP BY c.id
```

---

#### FITUR 4: Analitik Mata Pelajaran

**Route:** `GET /kepala-sekolah/akademik/mata-pelajaran`
**Controller:** `KepalaSekolah\AcademicController@mataPelajaran`
**View:** `resources/views/pages/kepala-sekolah/academic/mata-pelajaran.blade.php`

**Deskripsi:** Matriks ketercapaian target per mata pelajaran dengan visualisasi heatmap.

**Komponen:**

| Komponen | Tipe | Keterangan |
|----------|------|------------|
| Matriks Heatmap | Table + Color Coding | Baris: Mata Pelajaran, Kolom: Komponen Nilai. Warna: Hijau (>80), Kuning (60-80), Merah (<60) |
| Distribusi di atas/bawah KKM | Progress Bar | Persentase siswa per mapel yang di atas/bawah KKM |
| Perbandingan antar Kelas | Grouped Bar Chart | Perbandingan rata-rata per mapel antar kelas paralel |
| Detail per Mata Pelajaran | Drill-down | Klik mapel → detail distribusi nilai per kelas |

---

#### FITUR 5: Status Penilaian Guru

**Route:** `GET /kepala-sekolah/supervisi/penilaian`
**Controller:** `KepalaSekolah\SupervisionController@penilaianStatus`
**View:** `resources/views/pages/kepala-sekolah/supervisi/penilaian.blade.php`

**Deskripsi:** Monitoring ketepatan waktu guru dalam menginput nilai.

**Komponen:**

| Komponen | Tipe | Keterangan |
|----------|------|------------|
| Status Overview | Stat Cards | Total guru sudah input / belum input / terlambat |
| Tabel Status Input | Data Table | Guru, Kelas, Mapel, Status Input, Deadline, Keterlambatan |
| Badge Status | Badge | ✅ Selesai / ⏳ Pending / ⚠️ Terlambat |
| Alert Guru Terlambat | Alert Card | Daftar guru yang belum input melewati deadline |

**Query Logic:**
```
// Guru yang belum input nilai untuk kelas/mapel tertentu
SELECT t.id, u.name, c.name as class_name, s.name as subject_name,
  ts.deadline_input_date,
  CASE
    WHEN sg.id IS NULL AND NOW() > ts.deadline_input_date THEN 'terlambat'
    WHEN sg.id IS NULL THEN 'pending'
    ELSE 'selesai'
  END as status
FROM teacher_subjects ts
JOIN teachers t ON ts.teacher_id = t.id
JOIN users u ON t.user_id = u.id
JOIN classes c ON ts.class_id = c.id
JOIN subjects s ON ts.subject_id = s.id
LEFT JOIN student_grades sg ON sg.teacher_id = t.id
  AND sg.class_id = c.id
  AND sg.subject_id = s.id
  AND sg.semester_id = {active_semester_id}
WHERE c.school_id = {school_id}
AND ts.academic_year_id = {active_year_id}
```

---

#### FITUR 6: Laporan Kinerja Guru

**Route:** `GET /kepala-sekolah/supervisi/laporan-guru`
**Controller:** `KepalaSekolah\SupervisionController@laporanGuru`
**View:** `resources/views/pages/kepala-sekolah/supervisi/laporan-guru.blade.php`

**Deskripsi:** Ringkasan kinerja guru dari aspek pengajaran, penilaian, dan materi.

**Komponen:**

| Komponen | Tipe | Keterangan |
|----------|------|------------|
| Tabel Kinerja Guru | Data Table | Guru, Mapel, Kelas Diajar, Materi Diupload, Tugas Diberikan, Nilai Diinput, Feedback Diberikan, Skor Kinerja |
| Ranking Guru | Bar Chart | Perbandingan skor kinerja antar guru |
| Detail Guru | Drill-down | Klik guru → detail kinerja per kelas |
| Export Button | Button | Export laporan kinerja ke PDF/Excel |

**Skor Kinerja Guru (Algoritma):**
```
Skor Kinerja = (
  (Persentase nilai terinput * 0.30) +
  (Jumlah materi / Target materi * 0.25) +
  (Jumlah tugas / Target tugas * 0.20) +
  (Jumlah feedback / Target feedback * 0.15) +
  (Ketepatan waktu input * 0.10)
) * 100
```

---

#### FITUR 7: Feedback Strategis

**Route:**
- `GET /kepala-sekolah/feedback` — Daftar feedback
- `GET /kepala-sekolah/feedback/create` — Form buat feedback
- `POST /kepala-sekolah/feedback` — Simpan feedback
- `GET /kepala-sekolah/feedback/{id}` — Detail feedback

**Controller:** `KepalaSekolah\FeedbackController`
**View:** `resources/views/pages/kepala-sekolah/feedback/`

**Deskripsi:** Mekanisme feedback **top-down** dari Kepala Sekolah kepada Guru, Waka, atau Pengawas. Berbeda dengan feedback Guru→Siswa yang sudah ada, feedback ini bersifat **strategis/manajerial**.

**Tabel `feedbacks` yang sudah ada memiliki:**
- `teacher_id`, `student_id`, `subject_id`, `title`, `message`, `type`

**Modifikasi yang Diperlukan:**
Untuk mendukung feedback Kepala Sekolah → Guru/Waka/Pengawas, diperlukan extension pada tabel `feedbacks`:

```sql
-- Migration baru yang diperlukan:
ALTER TABLE feedbacks
  ADD COLUMN sender_id BIGINT NULL AFTER id,
  ADD COLUMN recipient_role VARCHAR(50) NULL AFTER sender_id,
  ADD COLUMN recipient_id BIGINT NULL AFTER recipient_role,
  ADD COLUMN category ENUM('strategic', 'academic', 'operational', 'recognition') DEFAULT 'academic',
  ADD COLUMN priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
  ADD COLUMN status ENUM('draft', 'sent', 'acknowledged', 'actioned') DEFAULT 'draft',
  ADD COLUMN action_plan TEXT NULL AFTER status,
  ADD COLUMN action_deadline DATE NULL AFTER action_plan;
```

**Komponen Form:**
- **Penerima:** Dropdown (Guru / Waka / Pengawas)
- **Kategori:** Radio (Strategis / Akademik / Operasional / Penghargaan)
- **Prioritas:** Radio (Rendah / Sedang / Tinggi / Urgent)
- **Judul:** Text input
- **Pesan:** Textarea
- **Rencana Aksi:** Textarea (opsional)
- **Deadline Aksi:** Date picker (opsional)

---

#### FITUR 8: Rencana Aksi

**Route:**
- `GET /kepala-sekolah/rencana-aksi` — Daftar rencana aksi
- `GET /kepala-sekolah/rencana-aksi/create` — Form buat rencana aksi
- `POST /kepala-sekolah/rencana-aksi` — Simpan rencana aksi
- `PATCH /kepala-sekolah/rencana-aksi/{id}` — Update status rencana aksi

**Controller:** `KepalaSekolah\ActionPlanController`
**View:** `resources/views/pages/kepala-sekolah/rencana-aksi/`

**Deskripsi:** Dokumen rencana aksi manajerial yang dibuat berdasarkan data monitoring.

**Model Baru: `ActionPlan`**

```php
// Schema:
// id, school_id, user_id (creator), title, description,
// target_role (guru/waka/pengawas), target_user_id (nullable),
// category (academic/character/memorization/operational),
// priority (low/medium/high/urgent),
// status (draft/in_progress/completed/cancelled),
// start_date, due_date, completed_at,
// notes, created_at, updated_at
```

**Komponen:**
- Kanban Board (Draft → In Progress → Completed)
- Form buat rencana aksi dengan target
- Timeline progress

---

#### FITUR 9: Evaluasi Sekolah

**Route:**
- `GET /kepala-sekolah/evaluasi` — Daftar evaluasi
- `POST /kepala-sekolah/evaluasi` — Simpan evaluasi

**Controller:** `KepalaSekolah\EvaluationController`
**View:** `resources/views/pages/kepala-sekolah/evaluasi/`

**Deskripsi:** Kepala Sekolah membuat evaluasi sekolah menggunakan model `SchoolEvaluation` yang sudah ada (`user_id`, `title`, `content`).

---

#### FITUR 10: Laporan & Export

**Route:**
- `GET /kepala-sekolah/laporan` — Index laporan
- `GET /kepala-sekolah/laporan/rekap-mingguan` — Rekap mingguan
- `GET /kepala-sekolah/laporan/rekap-bulanan` — Rekap bulanan
- `GET /kepala-sekolah/laporan/rekap-semester` — Rekap semester
- `GET /kepala-sekolah/laporan/export/{type}/{format}` — Export PDF/Excel

**Controller:** `KepalaSekolah\ReportController`
**View:** `resources/views/pages/kepala-sekolah/laporan/`

**Deskripsi:** Halaman konsolidasi laporan dengan kemampuan export.

**Library yang Diperlukan:**
- **PDF:** `barryvdh/laravel-dompdf` atau `livewire/livewire` + PDF component
- **Excel:** `maatwebsite/excel`

**Template Export:**

**PDF Rekap Nilai:**
```
┌──────────────────────────────────────────────┐
│  [Logo Sekolah]  LAPORAN REKAPITULASI NILAI  │
│  Nama Sekolah      Tahun: 2026/2027         │
│                      Semester: Ganjil        │
│──────────────────────────────────────────────│
│  Filter: Kelas X IPA 1, Semua Mapel         │
│──────────────────────────────────────────────│
│  No | Nama   | Pre | Tugas | Post | Kar  |  │
│     |        | test|       | test | Haf  |  │
│─────│────────│─────│───────│──────│──────│  │
│  1  | Budi   | 85  | 90    | 88   | 85  |  │
│  2  | Andi   | 78  | 82    | 80   | 90  |  │
│  ...                                            │
│──────────────────────────────────────────────│
│  Rata-rata Kelas: 82.3                        │
│  Disetujui oleh: [Nama Kepala Sekolah]       │
│  Tanggal: 01 September 2026                   │
└──────────────────────────────────────────────┘
```

---

#### FITUR 11: Profil Saya

**Route:**
- `GET /kepala-sekolah/profil` — Lihat profil
- `PUT /kepala-sekolah/profil` — Update profil

**Controller:** `KepalaSekolah\ProfileController`
**View:** `resources/views/pages/kepala-sekolah/profil.blade.php`

**Deskripsi:** Manajemen profil Kepala Sekolah (re-use komponen profile edit dari Breeze).

---

## 4. ALUR KERJA USER (USER JOURNEY / FLOW)

### 4.1 Alur Login & Akses Dashboard

```
┌─────────┐     ┌──────────────┐     ┌──────────────────┐     ┌───────────────────┐
│  Login   │────▶│ Auth Session  │────▶│ Role Detection   │────▶│ Redirect to       │
│  Page    │     │ Controller   │     │ kepala_sekolah   │     │ /kepala-sekolah/  │
│          │     │ ::store()    │     │                  │     │ dashboard         │
└─────────┘     └──────────────┘     └──────────────────┘     └───────────────────┘
```

**Detail Alur:**

1. User membuka `/login`
2. Memasukkan email + password
3. `AuthenticatedSessionController::store()` memproses autentikasi
4. Deteksi `Auth::user()->role->name === 'kepala_sekolah'`
5. Redirect ke `route('kepala-sekolah.dashboard')`
6. Layout `app.blade.php` mendeteksi role → render `<x-sidebars.kepala-sekolah />`

### 4.2 Alur Monitoring Rekapitulasi Nilai

```
┌──────────────┐     ┌────────────────┐     ┌──────────────┐     ┌──────────────┐
│ Dashboard    │────▶│ Klik "Rekap   │────▶│ Filter Form  │────▶│ Tabel Rekap  │
│ Eksekutif    │     │ Nilai"        │     │ (TA/Smt/Kls) │     │ Nilai Detail │
└──────────────┘     └────────────────┘     └──────────────┘     └──────┬───────┘
                                                                       │
                                              ┌─────────────────────────┤
                                              │                         │
                                              ▼                         ▼
                                     ┌──────────────┐         ┌──────────────┐
                                     │ Export PDF    │         │ Export Excel │
                                     │              │         │              │
                                     └──────────────┘         └──────────────┘
```

**Langkah Detail:**

1. Kepala Sekolah melihat Dashboard Eksekutif
2. Klik menu "Rekapitulasi Nilai" di sidebar
3. Halaman menampilkan form filter default (TA aktif, Semester aktif)
4. User memilih filter: Kelas → "X IPA 1", Mata Pelajaran → "Semua"
5. Tabel rekap menampilkan data siswa + komponen nilai
6. User klik tombol "Export PDF" → File PDF diunduh
7. Atau user klik "Export Excel" → File Excel diunduh

### 4.3 Alur Pemberian Feedback Strategis

```
┌──────────────┐     ┌────────────────┐     ┌──────────────┐     ┌──────────────┐
│ Supervisi    │────▶│ Klik "Buat    │────▶│ Form Feedback │────▶│ Submit       │
│ Kinerja Guru │     │ Feedback"     │     │ Strategis     │     │ Feedback     │
└──────────────┘     └────────────────┘     └──────────────┘     └──────┬───────┘
                                                                       │
                                                                       ▼
                                                              ┌──────────────┐
                                                              │ Notifikasi   │
                                                              │ ke Guru/Waka │
                                                              └──────────────┘
```

**Langkah Detail:**

1. Kepala Sekolah mengakses menu "Supervisi Kinerja Guru"
2. Melihat daftar guru + status penilaian
3. Menemukan 3 guru belum input nilai (terlambat)
4. Klik tombol "Buat Feedback" → Form muncul
5. Isi form:
   - Penerima: Pilih guru yang terlambat
   - Kategori: Operasional
   - Prioritas: Tinggi
   - Judul: "Keterlambatan Input Nilai UTS"
   - Pesan: "Mohon segera menyelesaikan input nilai UTS..."
   - Rencana Aksi: "Input nilai selesai sebelum 5 September 2026"
   - Deadline: 5 September 2026
6. Klik "Kirim" → Feedback tersimpan dengan status `sent`
7. Guru menerima notifikasi di dashboard mereka

### 4.4 Alur Membuat Rencana Aksi

```
┌──────────────┐     ┌────────────────┐     ┌──────────────┐     ┌──────────────┐
│ Dashboard    │────▶│ Lihat Alert   │────▶│ Klik "Buat  │────▶│ Form Rencana │
│ (Intervensi) │     │ Intervensi    │     │ Rencana Aksi"│     │ Aksi         │
└──────────────┘     └────────────────┘     └──────────────┘     └──────┬───────┘
                                                                       │
                                                                       ▼
                                                              ┌──────────────┐
                                                              │ Kanban Board │
                                                              │ Rencana Aksi │
                                                              └──────────────┘
```

### 4.5 Alur Persetujuan Laporan

```
┌──────────────┐     ┌────────────────┐     ┌──────────────┐     ┌──────────────┐
│ Laporan      │────▶│ Preview       │────▶│ Klik         │────▶│ Laporan      │
│ Rekap        │     │ Laporan       │     │ "Setujui"    │     │ Disetujui ✓  │
└──────────────┘     └────────────────┘     └──────────────┘     └──────────────┘
                                                                       │
                                                                       ▼
                                                              ┌──────────────┐
                                                              │ Export PDF   │
                                                              │ (Disetujui)  │
                                                              └──────────────┘
```

### 4.6 Alur Lengkap: Login → Monitoring → Feedback → Export

```
[LOGIN]
   │
   ▼
[DASHBOARD EKSEKUTIF]
   │
   ├─── Lihat rata-rata sekolah: 82.5 (+1.2 dari smt lalu)
   ├─── Lihat tren 6 bulan: grafik naik
   ├─── Lihat alert: "3 guru belum input nilai UTS"
   │
   ▼
[MENU: REKAPITULASI NILAI]
   │
   ├─── Filter: Kelas XII IPA
   ├─── Lihat tabel rekap: rata-rata 78.3
   ├─── Klik nama siswa: Ahmad (65.2) → detail
   │
   ▼
[MENU: PERKEMBANGAN SISWA]
   │
   ├─── Lihat tren Ahmad: turun dari 78 → 65
   ├─── Lihat distribusi karakter: 12 siswa di bawah target
   │
   ▼
[MENU: SUPERVISI KINERJA GURU]
   │
   ├─── Lihat status: 5 dari 12 guru sudah input
   ├─── 3 guru terlambat: Pak Budi, Pak Andi, Ibu Sari
   │
   ├─── KLIK "BUAT FEEDBACK" ──┐
   │                            │
   │   ┌────────────────────────┘
   │   │
   │   ▼
   │  [FORM FEEDBACK]
   │   ├── Penerima: Pak Budi
   │   ├── Kategori: Operasional
   │   ├── Prioritas: Tinggi
   │   ├── Judul: Keterlambatan Input Nilai
   │   ├── Pesan: Mohon segera input...
   │   └── SUBMIT → Feedback terkirim
   │
   ▼
[MENU: LAPORAN]
   │
   ├─── Pilih "Rekap Semester"
   ├─── Preview laporan
   ├─── KLIK "SETUJUI" → Laporan disetujui
   ├─── KLIK "EXPORT PDF" → File PDF diunduh
   │
   ▼
[LOGOUT]
```

---

## 5. RANCANGAN TAMPILAN / UI WIREFRAME (KONSEPTUAL)

### 5.1 Layout Umum (Consistent Across All Pages)

```
┌──────────────────────────────────────────────────────────────────────┐
│  ┌─────────────┐  ┌──────────────────────────────────────────────┐  │
│  │             │  │  ☰  |  [Page Title]           🔍  | 👤 User │  │
│  │  LOGO       │  ├──────────────────────────────────────────────┤  │
│  │  SinergiEdu │  │                                              │  │
│  │             │  │              PAGE CONTENT                     │  │
│  │ ─────────── │  │                                              │  │
│  │ 🏠 Dashboard│  │                                              │  │
│  │             │  │                                              │  │
│  │ 📊 Monitor  │  │                                              │  │
│  │  ├ Rekap   │  │                                              │  │
│  │  ├ Perkem. │  │                                              │  │
│  │  └ Mapel   │  │                                              │  │
│  │             │  │                                              │  │
│  │ 👨‍🏫 Supervisi│  │                                              │  │
│  │  ├ Penilai │  │                                              │  │
│  │  └ Laporan │  │                                              │  │
│  │             │  │                                              │  │
│  │ 📝 Evaluasi │  │                                              │  │
│  │  ├ Feedback│  │                                              │  │
│  │  ├ Rencana │  │                                              │  │
│  │  └ Evaluasi│  │                                              │  │
│  │             │  │                                              │  │
│  │ 📄 Laporan  │  │                                              │  │
│  │ ⚙️ Profil   │  │                                              │  │
│  │             │  │                                              │  │
│  │ ─────────── │  │                                              │  │
│  │ 👤 Kepala  │  │                                              │  │
│  │    Sekolah │  │                                              │  │
│  │ ▾ Profil   │  │                                              │  │
│  │   Logout   │  │                                              │  │
│  └─────────────┘  └──────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────────────┘

Sidebar width: 230px (consistent with existing)
Mobile: off-canvas sidebar via Alpine.js
```

### 5.2 Dashboard Eksekutif — Wireframe

```
┌──────────────────────────────────────────────────────────────────────┐
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░  │   │
│  │  ░░  SELAMAT DATANG, BAPAK/IBU KEPALA SEKOLAH!          ░░  │   │
│  │  ░░  Ikhtisar Eksekutif Performa Sekolah                ░░  │   │
│  │  ░░                                                      ░░  │   │
│  │  ░░  ┌─────────────┐  ┌─────────────┐                   ░░  │   │
│  │  ░░  │ Rata-rata   │  │ Kehadiran   │                   ░░  │   │
│  │  ░░  │ Sekolah     │  │ Agregat     │                   ░░  │   │
│  │  ░░  │   82.5      │  │   96%       │                   ░░  │   │
│  │  ░░  │ +1.2 ↑      │  │ Stabil →    │                   ░░  │   │
│  │  ░░  └─────────────┘  └─────────────┘                   ░░  │   │
│  │  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░  │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                                                      │
│  ┌───────────┐ ┌───────────┐ ┌───────────┐ ┌───────────┐          │
│  │ 📊 12     │ │ 👨‍🎓 450    │ │ 🏫 15     │ │ ⏰ 96%   │          │
│  │ Guru      │ │ Siswa     │ │ Kelas     │ │ Kehadiran │          │
│  │ Aktif     │ │ Aktif     │ │ Aktif     │ │ Rata-rata │          │
│  └───────────┘ └───────────┘ └───────────┘ └───────────┘          │
│                                                                      │
│  ┌──────────────────────────────┐ ┌──────────────────────────────┐ │
│  │                              │ │                              │ │
│  │  📈 Tren Nilai 6 Bulan      │ │  🏆 Peringkat Kelas          │ │
│  │  ┌────────────────────────┐ │ │  ┌────┬─────────┬──────────┐ │ │
│  │  │  ╱╲                    │ │ │  │ #  │ Kelas   │ Rata-rata│ │ │
│  │  │ ╱  ╲  ╱╲              │ │ │  ├────┼─────────┼──────────┤ │ │
│  │  │╱    ╲╱  ╲    ╱╲      │ │ │  │ 1  │ XII IPA │ 85.2     │ │ │
│  │  │          ╲  ╱  ╲     │ │ │  │ 2  │ XI IPA  │ 82.1     │ │ │
│  │  │           ╲╱    ╲╱   │ │ │  │ 3  │ XII IPS │ 80.5     │ │ │
│  │  │                     │ │ │  │ ...│ ...     │ ...      │ │ │
│  │  └────────────────────────┘ │ │  └────┴─────────┴──────────┘ │ │
│  │  Jul  Agu  Sep  Okt  Nov   │ │                              │ │
│  │                              │ │  [Lihat Semua →]             │ │
│  └──────────────────────────────┘ └──────────────────────────────┘ │
│                                                                      │
│  ┌──────────────────────────────┐ ┌──────────────────────────────┐ │
│  │                              │ │ ⚠️  INTERVENSI DIPERLUKAN    │ │
│  │  📊 Komponen Nilai Sekolah  │ │ ┌──────────────────────────┐ │ │
│  │                              │ │ │ 🔴 3 Guru Terlambat     │ │ │
│  │  Pre-test   ████████░░ 78  │ │ │    Input Nilai UTS       │ │ │
│  │  Tugas      █████████░ 82  │ │ ├──────────────────────────┤ │ │
│  │  Post-test  ████████░░ 80  │ │ │ 🟡 11 IPS 1: Rata-rata  │ │ │
│  │  Karakter   █████████░ 85  │ │ │    turun -12% minggu ini │ │ │
│  │  Hafalan    ███████░░░ 72  │ │ ├──────────────────────────┤ │ │
│  │                              │ │ │ 🟠 5 Siswa di bawah     │ │ │
│  │  [Buka Analitik Penuh →]    │ │ │    KKM semester ini      │ │ │
│  └──────────────────────────────┘ └──────────────────────────────┘ │
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │  🔗 LAPORAN CEPAT                                          │   │
│  │  ┌─────────────────────┐  ┌─────────────────────┐          │   │
│  │  │ 📄 Rekap Nilai      │  │ 📊 Laporan Kinerja  │          │   │
│  │  │    Semester         │  │    Guru             │          │   │
│  │  └─────────────────────┘  └─────────────────────┘          │   │
│  │  ┌─────────────────────┐  ┌─────────────────────┐          │   │
│  │  │ 📋 Rekap Mingguan   │  │ 📝 Evaluasi Sekolah │          │   │
│  │  └─────────────────────┘  └─────────────────────┘          │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                                                      │
└──────────────────────────────────────────────────────────────────────┘
```

### 5.3 Rekapitulasi Nilai — Wireframe

```
┌──────────────────────────────────────────────────────────────────────┐
│  REKAPITULASI NILAI SISWA                                           │
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │  Filter:                                                     │   │
│  │  [Tahun Ajaran ▾] [Semester ▾] [Kelas ▾] [Mapel ▾] [🔍]   │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │  Export: [📄 PDF] [📊 Excel]     Total: 35 Siswa            │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │  No │ Nama      │ NISN    │ Kelas  │Pre │Tugas│Post│Kar│Haf│ │   │
│  │     │           │         │        │test│     │test│   │   │ │   │
│  │─────│───────────│─────────│────────│────│─────│────│───│───│ │   │
│  │  1  │ Ahmad     │ 0012345 │XII IPA │ 85 │  90 │ 88 │85 │80 │ │   │
│  │     │ Ridwan    │         │   1    │    │     │    │   │   │ │   │
│  │     │           │         │        │    │     │    │   │   │ │   │
│  │  ✅ 85.6 │ Lulus                                            │   │
│  │─────│───────────│─────────│────────│────│─────│────│───│───│ │   │
│  │  2  │ Siti     │ 0012346 │XII IPA │ 65 │  70 │ 62 │55 │50 │ │   │
│  │     │ Nurhaliza│         │   1    │    │     │    │   │   │ │   │
│  │     │           │         │        │    │     │    │   │   │ │   │
│  │  ⚠️ 60.4 │ Perlu Perhatian                                  │   │
│  │─────│───────────│─────────│────────│────│─────│────│───│───│ │   │
│  │  ... │           │         │        │    │     │    │   │   │ │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                                                      │
│  Rata-rata Kelas: 82.3 | Lulus: 28 (80%) | Perlu Perhatian: 7 (20%)│
└──────────────────────────────────────────────────────────────────────┘
```

### 5.4 Supervisi Kinerja Guru — Wireframe

```
┌──────────────────────────────────────────────────────────────────────┐
│  SUPERVISI KINERJA GURU                                             │
│                                                                      │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐                   │
│  │ ✅ 8/12     │ │ ⏳ 1/12     │ │ ⚠️ 3/12     │                   │
│  │ Sudah Input │ │ Pending     │ │ Terlambat   │                   │
│  └─────────────┘ └─────────────┘ └─────────────┘                   │
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │  [📋 Status Penilaian] [📊 Laporan Kinerja] [+ Feedback]    │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │  Guru       │ Mapel    │ Kelas │ Status  │ Deadline │ Aksi  │   │
│  │─────────────│──────────│───────│─────────│──────────│───────│   │
│  │ Pak Budi    │ Matemat. │ XII 1 │ ⚠️ Late │ 28/08    │ 💬    │   │
│  │ Pak Andi    │ Fisika   │ XII 2 │ ⚠️ Late │ 28/08    │ 💬    │   │
│  │ Ibu Sari    │ Biologi  │ XII 3 │ ⚠️ Late │ 28/08    │ 💬    │   │
│  │─────────────│──────────│───────│─────────│──────────│───────│   │
│  │ Pak Deddy  │ Kimia    │ XII 1 │ ✅ Done │ 28/08    │ 👁️    │   │
│  │ Ibu Rina   │ B.Inggris│ XII 2 │ ✅ Done │ 28/08    │ 👁️    │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                                                      │
│  💬 = Kirim Feedback  |  👁️ = Lihat Detail                          │
└──────────────────────────────────────────────────────────────────────┘
```

### 5.5 Form Feedback Strategis — Wireframe

```
┌──────────────────────────────────────────────────────────────────────┐
│  BUAT FEEDBACK STRATEGIS                                            │
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                                                              │   │
│  │  Penerima *                                                  │   │
│  │  ┌──────────────────────────────────────────────────────┐   │   │
│  │  │ [▾ Pilih Guru/Waka/Pengawas                    ]    │   │   │
│  │  └──────────────────────────────────────────────────────┘   │   │
│  │                                                              │   │
│  │  Kategori *              Prioritas *                         │   │
│  │  ○ Strategis             ○ Rendah                            │   │
│  │  ● Operasional           ○ Sedang                            │   │
│  │  ○ Akademik              ● Tinggi                            │   │
│  │  ○ Penghargaan           ○ Urgent                            │   │
│  │                                                              │   │
│  │  Judul *                                                    │   │
│  │  ┌──────────────────────────────────────────────────────┐   │   │
│  │  │ Keterlambatan Input Nilai UTS                        │   │   │
│  │  └──────────────────────────────────────────────────────┘   │   │
│  │                                                              │   │
│  │  Pesan *                                                    │   │
│  │  ┌──────────────────────────────────────────────────────┐   │   │
│  │  │ Bapak/Ibu Guru yang terhormat,                       │   │   │
│  │  │                                                        │   │   │
│  │  │ Berdasarkan monitoring sistem, ditemukan bahwa...      │   │   │
│  │  │                                                        │   │   │
│  │  │                                                        │   │   │
│  │  └──────────────────────────────────────────────────────┘   │   │
│  │                                                              │   │
│  │  Rencana Aksi (Opsional)                                    │   │
│  │  ┌──────────────────────────────────────────────────────┐   │   │
│  │  │ Input seluruh nilai UTS paling lambat 5 Sept 2026    │   │   │
│  │  └──────────────────────────────────────────────────────┘   │   │
│  │                                                              │   │
│  │  Deadline Aksi (Opsional)                                   │   │
│  │  ┌──────────────────────────────────────────────────────┐   │   │
│  │  │ 📅 05/09/2026                                         │   │   │
│  │  └──────────────────────────────────────────────────────┘   │   │
│  │                                                              │   │
│  │  ┌──────────────┐  ┌────────────────┐                       │   │
│  │  │  Simpan Draft │  │  📨 Kirim      │                       │   │
│  │  └──────────────┘  └────────────────┘                       │   │
│  │                                                              │   │
│  └──────────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────────┘
```

### 5.6 Rencana Aksi — Kanban Board Wireframe

```
┌──────────────────────────────────────────────────────────────────────┐
│  RENCANA AKSI  [+ Buat Baru]                                        │
│                                                                      │
│  ┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐       │
│  │ 📋 DRAFT (2)    │ │ 🔄 IN PROGRESS  │ │ ✅ COMPLETED (5)│       │
│  │                 │ │     (3)         │ │                 │       │
│  │ ┌─────────────┐ │ │ ┌─────────────┐ │ │ ┌─────────────┐ │       │
│  │ │ Evaluasi    │ │ │ │ Perbaikan   │ │ │ │ Workshop    │ │       │
│  │ │ Pengajaran  │ │ │ │ Metode      │ │ │ │ Kurikulum   │ │       │
│  │ │ 📅 10/09    │ │ │ │ Ajar        │ │ │ │ ✅ 20/08    │ │       │
│  │ │ 👨‍🏫 Guru    │ │ │ │ 📅 05/09    │ │ │ │ 👨‍🏫 Waka    │ │       │
│  │ └─────────────┘ │ │ │ 👨‍🏫 Pak Budi│ │ │ └─────────────┘ │       │
│  │ ┌─────────────┐ │ │ └─────────────┘ │ │                 │       │
│  │ │ Perbaikan   │ │ │ ┌─────────────┐ │ │                 │       │
│  │ │ Input Nilai │ │ │ │ Sosialisasi │ │ │                 │       │
│  │ │ 📅 15/09    │ │ │ │ Hafalan     │ │ │                 │       │
│  │ │ 👨‍🏫 Guru    │ │ │ │ 📅 01/09    │ │ │                 │       │
│  │ └─────────────┘ │ │ │ 👨‍🏫 Waka    │ │ │                 │       │
│  │                 │ │ └─────────────┘ │ │                 │       │
│  └─────────────────┘ └─────────────────┘ └─────────────────┘       │
└──────────────────────────────────────────────────────────────────────┘
```

### 5.7 Laporan & Export — Wireframe

```
┌──────────────────────────────────────────────────────────────────────┐
│  LAPORAN SEKOLAH                                                    │
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │  [📋 Rekap Mingguan] [📊 Rekap Bulanan] [📈 Rekap Semester] │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                                                              │   │
│  │  📄 Rekapitulasi Akademik Semester                           │   │
│  │  ─────────────────────────────────────────────               │   │
│  │  Periode: Ganjil 2026/2027                                   │   │
│  │  Jumlah Siswa: 450 | Jumlah Kelas: 15 | Rata-rata: 82.5     │   │
│  │                                                              │   │
│  │  ┌────────────────────────────────────────────────────────┐  │   │
│  │  │ Ringkasan:                                             │  │   │
│  │  │ • Rata-rata Pre-test:  78.3                            │  │   │
│  │  │ • Rata-rata Tugas:     82.1                            │  │   │
│  │  │ • Rata-rata Post-test: 80.5                            │  │   │
│  │  │ • Rata-rata Karakter:  85.2                            │  │   │
│  │  │ • Rata-rata Hafalan:   72.8                            │  │   │
│  │  └────────────────────────────────────────────────────────┘  │   │
│  │                                                              │   │
│  │  Status: ✅ Disetujui oleh Kepala Sekolah                   │   │
│  │  Tanggal Persetujuan: 01 September 2026                      │   │
│  │                                                              │   │
│  │  ┌──────────────┐  ┌────────────────┐                       │   │
│  │  │ 📄 Export PDF │  │ 📊 Export Excel │                       │   │
│  │  └──────────────┘  └────────────────┘                       │   │
│  │                                                              │   │
│  └──────────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────────┘
```

---

## 6. SPESIFIKASI TEKNIS IMPLEMENTASI

### 6.1 Struktur Route

```php
// routes/web/kepala_sekolah.php

use App\Http\Controllers\KepalaSekolah;
use Illuminate\Support\Facades\Route;

Route::prefix('kepala-sekolah')
    ->name('kepala-sekolah.')
    ->middleware(['auth', 'verified', 'role:kepala_sekolah'])
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [KepalaSekolah\DashboardController::class, 'index'])
        ->name('dashboard');

    // Monitoring Akademik
    Route::prefix('akademik')->name('academic.')->group(function () {
        Route::get('/rekap', [KepalaSekolah\AcademicController::class, 'rekap'])
            ->name('rekap');
        Route::get('/perkembangan', [KepalaSekolah\AcademicController::class, 'perkembangan'])
            ->name('perkembangan');
        Route::get('/mata-pelajaran', [KepalaSekolah\AcademicController::class, 'mataPelajaran'])
            ->name('subjects');
        Route::get('/siswa/{student}', [KepalaSekolah\AcademicController::class, 'studentDetail'])
            ->name('student-detail');
    });

    // Supervisi Kinerja Guru
    Route::prefix('supervisi')->name('supervision.')->group(function () {
        Route::get('/penilaian', [KepalaSekolah\SupervisionController::class, 'penilaianStatus'])
            ->name('grading-status');
        Route::get('/laporan-guru', [KepalaSekolah\SupervisionController::class, 'laporanGuru'])
            ->name('teacher-report');
        Route::get('/guru/{teacher}', [KepalaSekolah\SupervisionController::class, 'teacherDetail'])
            ->name('teacher-detail');
    });

    // Feedback Strategis
    Route::resource('feedback', KepalaSekolah\FeedbackController::class)
        ->except(['edit', 'update', 'destroy']);

    // Rencana Aksi
    Route::resource('rencana-aksi', KepalaSekolah\ActionPlanController::class)
        ->except(['edit']);
    Route::patch('/rencana-aksi/{actionPlan}/status', [KepalaSekolah\ActionPlanController::class, 'updateStatus'])
        ->name('action-plan.update-status');

    // Evaluasi Sekolah
    Route::resource('evaluasi', KepalaSekolah\EvaluationController::class)
        ->except(['edit', 'update', 'destroy']);

    // Laporan & Export
    Route::prefix('laporan')->name('reports.')->group(function () {
        Route::get('/', [KepalaSekolah\ReportController::class, 'index'])
            ->name('index');
        Route::get('/rekap-mingguan', [KepalaSekolah\ReportController::class, 'weeklyRecap'])
            ->name('weekly');
        Route::get('/rekap-bulanan', [KepalaSekolah\ReportController::class, 'monthlyRecap'])
            ->name('monthly');
        Route::get('/rekap-semester', [KepalaSekolah\ReportController::class, 'semesterRecap'])
            ->name('semester');
        Route::get('/export/{type}/{format}', [KepalaSekolah\ReportController::class, 'export'])
            ->name('export');
    });

    // Profil
    Route::get('/profil', [KepalaSekolah\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::put('/profil', [KepalaSekolah\ProfileController::class, 'update'])
        ->name('profile.update');
});
```

### 6.2 Controller Structure

```
app/Http/Controllers/KepalaSekolah/
├── DashboardController.php
├── AcademicController.php
├── SupervisionController.php
├── FeedbackController.php
├── ActionPlanController.php
├── EvaluationController.php
├── ReportController.php
└── ProfileController.php
```

### 6.3 Service Layer

```
app/Services/
├── KepalaSekolah/
│   ├── AcademicAggregatorService.php    // Agregasi data akademik
│   ├── SupervisionService.php           // Logika supervisi guru
│   ├── ReportGeneratorService.php       // Generate laporan
│   └── ExportService.php               // Export PDF/Excel
└── (existing) TenantService.php
```

### 6.4 Middleware Registration

```php
// bootstrap/app.php — tambahkan route file
Route::middleware(['web', 'tenant'])
    ->group(base_path('routes/web.php'));

// routes/web.php — register file route
require __DIR__.'/web/kepala_sekolah.php';
```

```php
// app/Http/Controllers/Auth/AuthenticatedSessionController.php
// Tambahkan case redirect:
case 'kepala_sekolah':
    return redirect()->intended(route('kepala-sekolah.dashboard'));
```

### 6.5 View Structure

```
resources/views/
├── components/
│   └── sidebars/
│       └── kepala-sekolah.blade.php          // Sidebar navigasi
├── pages/
│   └── kepala-sekolah/
│       ├── dashboard.blade.php               // Dashboard Eksekutif
│       ├── academic/
│       │   ├── rekap.blade.php               // Rekapitulasi Nilai
│       │   ├── perkembangan.blade.php        // Perkembangan Siswa
│       │   ├── mata-pelajaran.blade.php      // Analitik Mapel
│       │   └── student-detail.blade.php      // Detail Siswa
│       ├── supervisi/
│       │   ├── penilaian.blade.php           // Status Penilaian
│       │   ├── laporan-guru.blade.php        // Laporan Kinerja Guru
│       │   └── teacher-detail.blade.php      // Detail Guru
│       ├── feedback/
│       │   ├── index.blade.php               // Daftar Feedback
│       │   ├── create.blade.php              // Form Feedback
│       │   └── show.blade.php                // Detail Feedback
│       ├── rencana-aksi/
│       │   ├── index.blade.php               // Kanban Board
│       │   ├── create.blade.php              // Form Rencana Aksi
│       │   └── show.blade.php                // Detail Rencana Aksi
│       ├── evaluasi/
│       │   ├── index.blade.php               // Daftar Evaluasi
│       │   └── create.blade.php              // Form Evaluasi
│       ├── laporan/
│       │   ├── index.blade.php               // Index Laporan
│       │   ├── rekap-mingguan.blade.php
│       │   ├── rekap-bulanan.blade.php
│       │   └── rekap-semester.blade.php
│       └── profil/
│           └── edit.blade.php                // Profil Saya
```

### 6.6 Model Extensions

```php
// Model baru yang diperlukan:

// 1. ActionPlan (Rencana Aksi)
Schema::create('action_plans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('school_id')->constrained();
    $table->foreignId('user_id')->constrained(); // creator
    $table->string('title');
    $table->text('description')->nullable();
    $table->string('target_role')->nullable(); // guru/waka/pengawas
    $table->foreignId('target_user_id')->nullable()->constrained('users');
    $table->string('category')->default('academic'); // academic/character/memorization/operational
    $table->string('priority')->default('medium'); // low/medium/high/urgent
    $table->string('status')->default('draft'); // draft/in_progress/completed/cancelled
    $table->date('start_date')->nullable();
    $table->date('due_date')->nullable();
    $table->timestamp('completed_at')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
});

// 2. FeedbackExtension (extension ke tabel feedbacks yang ada)
Schema::table('feedbacks', function (Blueprint $table) {
    $table->foreignId('sender_id')->nullable()->after('id')->constrained('users');
    $table->string('recipient_role')->nullable()->after('sender_id');
    $table->foreignId('recipient_id')->nullable()->after('recipient_role')->constrained('users');
    $table->string('category')->default('academic')->after('type');
    $table->string('priority')->default('medium')->after('category');
    $table->string('status')->default('draft')->after('priority');
    $table->text('action_plan')->nullable()->after('status');
    $table->date('action_deadline')->nullable()->after('action_plan');
});

// 3. ReportApproval (Persetujuan Laporan)
Schema::create('report_approvals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('school_id')->constrained();
    $table->foreignId('user_id')->constrained(); // approver (kepala_sekolah)
    $table->string('report_type'); // weekly/monthly/semester
    $table->string('report_period'); // e.g., "2026-09-minggu-1"
    $table->string('status')->default('pending'); // pending/approved/rejected
    $table->timestamp('approved_at')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

### 6.7 Database Query Patterns

```php
// AcademicAggregatorService.php — Core Queries

public function getSchoolAverageGrade(int $schoolId, int $semesterId): float
{
    return StudentGrade::whereHas('student', fn($q) => $q->where('school_id', $schoolId))
        ->where('semester_id', $semesterId)
        ->selectRaw('AVG((pre_test_score + assignment_score + post_test_score + character_score + memorization_score) / 5) as avg')
        ->value('avg') ?? 0;
}

public function getClassRankings(int $schoolId, int $semesterId): Collection
{
    return StudentGrade::whereHas('classroom', fn($q) => $q->where('school_id', $schoolId))
        ->where('semester_id', $semesterId)
        ->join('classes', 'student_grades.class_id', '=', 'classes.id')
        ->select('classes.name', DB::raw('AVG((pre_test_score + assignment_score + post_test_score + character_score + memorization_score) / 5) as avg'))
        ->groupBy('classes.id', 'classes.name')
        ->orderByDesc('avg')
        ->get();
}

public function getComponentAverages(int $schoolId, int $semesterId): array
{
    return StudentGrade::whereHas('student', fn($q) => $q->where('school_id', $schoolId))
        ->where('semester_id', $semesterId)
        ->selectRaw('
            AVG(pre_test_score) as avg_pre_test,
            AVG(assignment_score) as avg_assignment,
            AVG(post_test_score) as avg_post_test,
            AVG(character_score) as avg_character,
            AVG(memorization_score) as avg_memorization
        ')
        ->first()
        ->toArray();
}

public function getGradingStatus(int $schoolId, int $academicYearId): array
{
    // Guru yang sudah input vs belum input
    $teacherSubjects = TeacherSubject::whereHas('classroom', fn($q) => $q->where('school_id', $schoolId))
        ->where('academic_year_id', $academicYearId)
        ->with(['teacher.user', 'classroom', 'subject'])
        ->get();

    $graded = $teacherSubjects->filter(fn($ts) =>
        StudentGrade::where('teacher_id', $ts->teacher_id)
            ->where('class_id', $ts->class_id)
            ->where('subject_id', $ts->subject_id)
            ->exists()
    );

    return [
        'total' => $teacherSubjects->count(),
        'graded' => $graded->count(),
        'pending' => $teacherSubjects->count() - $graded->count(),
        'details' => $teacherSubjects->map(fn($ts) => [
            'teacher' => $ts->teacher->user->name,
            'subject' => $ts->subject->name,
            'class' => $ts->classroom->name,
            'status' => $graded->contains($ts) ? 'completed' : 'pending',
        ]),
    ];
}
```

---

## 7. SKEMA DATA & RELASI

### 7.1 Entity Relationship (ERD) untuk Modul Kepala Sekolah

```
┌─────────────────────────────────────────────────────────────────────┐
│                        ERD MODUL KEPALA SEKOLAH                     │
└─────────────────────────────────────────────────────────────────────┘

┌──────────┐    ┌──────────┐    ┌──────────────────┐
│  schools │───<│  users   │───<│ kepala_sekolahs  │
│          │    │          │    │                  │
│ id       │    │ id       │    │ id               │
│ name     │    │ name     │    │ user_id (FK)     │
│ npsn     │    │ email    │    │ nip              │
│ ...      │    │ role_id  │    │ phone            │
└──────────┘    │ school_id│    │ address          │
                └──────────┘    └──────────────────┘
                      │
                      │ (role_id → roles)
                      ▼
                ┌──────────┐
                │  roles   │
                │          │
                │ id       │
                │ name     │ = 'kepala_sekolah'
                └──────────┘

┌──────────────────────┐    ┌──────────────────────┐
│   student_grades     │    │    feedbacks          │
│                      │    │  (extended)           │
│ id                   │    │ id                    │
│ student_id (FK)      │    │ sender_id (FK) ─── NEW
│ teacher_id (FK)      │    │ recipient_role ─ NEW
│ class_id (FK)        │    │ recipient_id (FK) ─ NEW
│ subject_id (FK)      │    │ teacher_id (FK)      │
│ academic_year_id(FK) │    │ student_id (FK)      │
│ semester_id (FK)     │    │ subject_id (FK)      │
│ pre_test_score       │    │ title                │
│ assignment_score     │    │ message              │
│ post_test_score      │    │ type                 │
│ character_score      │    │ category ─── NEW     │
│ memorization_score   │    │ priority ─── NEW     │
│ notes                │    │ status ──── NEW      │
└──────────────────────┘    │ action_plan ── NEW   │
                            │ action_deadline NEW  │
┌──────────────────────┐    └──────────────────────┘
│    action_plans      │         │
│  (NEW TABLE)         │         │ (sender_id/recipient_id → users)
│                      │    ┌──────────────────────┐
│ id                   │    │  report_approvals     │
│ school_id (FK)       │    │  (NEW TABLE)          │
│ user_id (FK)         │    │                       │
│ title                │    │ id                    │
│ description          │    │ school_id (FK)        │
│ target_role          │    │ user_id (FK)          │
│ target_user_id (FK)  │    │ report_type           │
│ category             │    │ report_period         │
│ priority             │    │ status                │
│ status               │    │ approved_at           │
│ start_date           │    │ notes                 │
│ due_date             │    └──────────────────────┘
│ completed_at         │
│ notes                │
└──────────────────────┘

Relasi yang Digunakan oleh Kepala Sekolah:

KepalaSekolah → User (BelongsTo)
KepalaSekolah → School (BelongsTo via User.school_id)

StudentGrade → Student → User → School (scope)
StudentGrade → Teacher → User
StudentGrade → Classroom
StudentGrade → Subject
StudentGrade → AcademicYear
StudentGrade → Semester

Feedback (extended) → User (sender)
Feedback (extended) → User (recipient)
Feedback → Teacher, Student, Subject

ActionPlan → User (creator)
ActionPlan → User (target)
ActionPlan → School

ReportApproval → User (approver)
ReportApproval → School
```

### 7.2 Model Baru: ActionPlan

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionPlan extends Model
{
    use \App\Traits\TenantScoped, HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'title',
        'description',
        'target_role',
        'target_user_id',
        'category',
        'priority',
        'status',
        'start_date',
        'due_date',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    // Accessors for badge styling
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'in_progress' => 'Dikerjakan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'slate',
            'in_progress' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'slate',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'slate',
            'medium' => 'blue',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'slate',
        };
    }
}
```

---

## 8. REKOMENDASI PENGEMBANGAN

### 8.1 Phase 1 — Foundation (Minggu 1-2)

| No | Task | Priority | Estimasi |
|----|------|----------|----------|
| 1 | Buat migration `create_action_plans_table` | High | 1 jam |
| 2 | Buat migration `extend_feedbacks_table` | High | 1 jam |
| 3 | Buat migration `create_report_approvals_table` | Medium | 1 jam |
| 4 | Buat model `ActionPlan` + update `Feedback` model | High | 2 jam |
| 5 | Buat route file `routes/web/kepala_sekolah.php` | High | 1 jam |
| 6 | Register route di `bootstrap/app.php` | High | 15 menit |
| 7 | Tambah redirect case di `AuthenticatedSessionController` | High | 15 menit |
| 8 | Buat sidebar `kepala-sekolah.blade.php` | High | 1 jam |
| 9 | Update `app.blade.php` sidebar dispatch | High | 15 menit |
| 10 | Buat `KepalaSekolah` base controller + service | High | 3 jam |

### 8.2 Phase 2 — Dashboard & Monitoring (Minggu 3-4)

| No | Task | Priority | Estimasi |
|----|------|----------|----------|
| 1 | Buat `AcademicAggregatorService` | High | 4 jam |
| 2 | Buat `DashboardController` + dashboard view | High | 4 jam |
| 3 | Buat halaman Rekapitulasi Nilai | High | 4 jam |
| 4 | Buat halaman Perkembangan Siswa | High | 4 jam |
| 5 | Buat halaman Analitik Mata Pelajaran | Medium | 3 jam |
| 6 | Integrasi Chart.js untuk visualisasi | Medium | 3 jam |

### 8.3 Phase 3 — Supervisi & Feedback (Minggu 5-6)

| No | Task | Priority | Estimasi |
|----|------|----------|----------|
| 1 | Buat `SupervisionController` + views | High | 4 jam |
| 2 | Buat `FeedbackController` + views | High | 4 jam |
| 3 | Buat `ActionPlanController` + Kanban view | High | 5 jam |
| 4 | Buat `EvaluationController` + views | Medium | 3 jam |
| 5 | Integrasi notifikasi feedback | Medium | 3 jam |

### 8.4 Phase 4 — Laporan & Export (Minggu 7-8)

| No | Task | Priority | Estimasi |
|----|------|----------|----------|
| 1 | Install `barryvdh/laravel-dompdf` | High | 30 menit |
| 2 | Install `maatwebsite/excel` | High | 30 menit |
| 3 | Buat `ReportGeneratorService` | High | 4 jam |
| 4 | Buat `ExportService` | High | 4 jam |
| 5 | Buat template PDF rekap nilai | High | 3 jam |
| 6 | Buat template Excel rekap nilai | High | 3 jam |
| 7 | Buat halaman laporan + export buttons | Medium | 3 jam |
| 8 | Buat `ProfileController` + profil view | Low | 2 jam |

### 8.5 Dependencies Baru yang Diperlukan

```bash
# PDF Export
composer require barryvdh/laravel-dompdf

# Excel Export
composer require maatwebsite/excel

# Chart.js sudah via CDN (tidak perlu install tambahan)
```

### 8.6 Testing Strategy

| No | Jenis Test | Scope | Tools |
|----|-----------|-------|-------|
| 1 | Unit Test | `AcademicAggregatorService` | PHPUnit |
| 2 | Feature Test | Route access control (role:kepala_sekolah) | PHPUnit + RefreshDatabase |
| 3 | Feature Test | Feedback CRUD flow | PHPUnit + RefreshDatabase |
| 4 | Feature Test | Export PDF/Excel | PHPUnit |
| 5 | Browser Test | Dashboard rendering | Laravel Dusk (opsional) |

---

**Dokumen ini merupakan panduan komprehensif untuk pengembangan modul Kepala Sekolah. Setiap bagian dirancang agar dapat langsung diimplementasikan oleh developer dengan mempertahankan konsistensi arsitektur dan gaya kode yang sudah ada dalam proyek SinergiEdu.**
