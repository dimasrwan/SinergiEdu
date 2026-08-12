# SinergiEdu Responsive Design Standards

These rules must be followed for ALL pages and ALL roles in the project.

## 1. Target Devices (Mobile-First Approach)
- Mobile kecil: 320px – 374px
- Mobile: 375px – 639px
- Tablet: 640px – 1023px
- Laptop: 1024px – 1279px
- Desktop: 1280px – 1535px
- Large desktop: 1536px ke atas

## 2. Mobile Behavior
- **Sidebar:** Berubah menjadi drawer yang dapat dibuka via menu button.
- **Content & Cards:** Menggunakan single column.
- **Form:** Menggunakan single column.
- **Grid:** Collapse secara natural.
- **Table:** Boleh menggunakan horizontal scrolling jika data membutuhkan banyak kolom (Responsive Table). Tidak boleh merusak layout.
- **Modal:** Tetap berada di dalam viewport, margin nyaman.
- **Dropdown & Button:** Tidak boleh keluar container (overflow).
- **Text:** Tidak boleh terpotong (harus bisa wrap).
- **Chart:** Harus responsive, tinggi dikurangi pada mobile.
- **Spacing:** Padding dan gap lebih kecil namun tetap nyaman (Touch-friendly UX).

## 3. Table Responsiveness
- Gunakan responsive horizontal scrolling untuk tabel dengan banyak kolom.
- Jangan mengecilkan font secara berlebihan.
- Pastikan: Header mudah dibaca, row nyaman di-tap, action column usable.
- Jika cocok, ubah tabel menjadi bentuk card pada mobile.

## 4. Form Responsiveness
- **Desktop:** 2 kolom jika sesuai.
- **Tablet:** 1 atau 2 kolom.
- **Mobile:** 1 kolom.
- **Input:** Width 100%, touch-friendly height, clear label, adequate spacing. Jangan membuat input terlalu kecil.

## 5. Dashboard Responsiveness
- **Desktop:** 4 kolom (atau menyesuaikan grid).
- **Tablet:** 2 kolom.
- **Mobile:** 1 kolom (stack). Jangan paksakan 4 card berjejer di mobile.

## 6. Typography & Spacing
- Gunakan responsive Tailwind typography. Jangan gunakan heading terlalu besar di mobile.
- Pastikan teks bisa wrap dan tombol tidak terpotong.
- Kurangi padding dan gap secara proporsional di mobile.

## 7. Touch UX
- Pastikan Button, Navigation, Dropdown, Checkbox, Radio, Table action, Pagination, Tabs, dan Modal sangat nyaman untuk disentuh (minimum touch target size).

## 8. Development & Testing
- Desain harus menggunakan Mobile-First Tailwind secara ketat (jangan mendesain untuk Desktop lalu memaksanya dikecilkan).
- Selalu lakukan visual check pada berbagai ukuran viewport. Mencegah adanya Horizontal Overflow.
