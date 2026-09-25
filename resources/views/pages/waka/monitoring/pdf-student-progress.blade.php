<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perkembangan Siswa - {{ $student->user->name }}</title>
    <style>
        @page {
            margin: 20px 25px;
            size: a4 landscape;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 4px 0;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 0;
            font-size: 11px;
            color: #475569;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 6px;
            font-size: 11px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #334155;
            width: 15%;
        }
        .info-colon {
            width: 2%;
            text-align: center;
        }
        .info-val {
            color: #0f172a;
            width: 33%;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 7px;
            text-align: left;
            font-size: 10px;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 9.5px;
        }
        .text-center {
            text-align: center !important;
        }
        .text-right {
            text-align: right !important;
        }
        .font-bold {
            font-weight: bold;
        }
        .badge-score {
            display: inline-block;
            padding: 2px 6px;
            font-weight: bold;
            border-radius: 4px;
            background-color: #e0f2fe;
            color: #0369a1;
        }
        .footer {
            margin-top: 25px;
            width: 100%;
            border-collapse: collapse;
        }
        .footer td {
            vertical-align: top;
            font-size: 11px;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-space {
            height: 50px;
        }
    </style>
</head>
<body>

    <!-- Header Dokumen -->
    <div class="header">
        <h1>Laporan Perkembangan Hasil Belajar Siswa</h1>
        <p>Sistem Informasi Akademik Terintegrasi — SinergiEdu</p>
    </div>

    <!-- Identitas Siswa -->
    <table class="info-table">
        <tr>
            <td class="info-label">Nama Siswa</td>
            <td class="info-colon">:</td>
            <td class="info-val font-bold">{{ $student->user->name }}</td>

            <td class="info-label">Tahun Ajaran</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ $activeYear->year ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">NIS / NISN</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}</td>

            <td class="info-label">Orang Tua / Wali</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ $student->parent->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Tanggal Cetak</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ date('d F Y, H:i') }} WIB</td>

            <td class="info-label">Status Penilaian</td>
            <td class="info-colon">:</td>
            <td class="info-val font-bold" style="color: #059669;">Terverifikasi Waka Kurikulum</td>
        </tr>
    </table>

    <!-- Tabel Nilai & Catatan Perkembangan -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Mata Pelajaran</th>
                <th style="width: 65px;">Pertemuan</th>
                <th style="width: 70px;">Tanggal</th>
                <th style="width: 50px;">Pre-Test</th>
                <th style="width: 50px;">Tugas</th>
                <th style="width: 50px;">Post-Test</th>
                <th style="width: 50px;">Karakter</th>
                <th style="width: 50px;">Hafalan</th>
                <th style="width: 55px;">Rata-Rata</th>
                <th>Catatan & Saran Guru</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grades as $index => $grade)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $grade->learningMeeting->subject->name ?? '-' }}</td>
                    <td class="text-center">Ke-{{ $grade->learningMeeting->meeting_number ?? '-' }}</td>
                    <td class="text-center">{{ $grade->learningMeeting->meeting_date?->format('d/m/Y') ?? '-' }}</td>
                    <td class="text-center">{{ $grade->pre_test_score ?? '-' }}</td>
                    <td class="text-center">{{ $grade->assignment_score ?? '-' }}</td>
                    <td class="text-center">{{ $grade->post_test_score ?? '-' }}</td>
                    <td class="text-center">{{ $grade->character_score ?? '-' }}</td>
                    <td class="text-center">{{ $grade->memorization_score ?? '-' }}</td>
                    <td class="text-center font-bold" style="background-color: #f8fafc; color: #0284c7;">
                        {{ $grade->average_score }}
                    </td>
                    <td style="color: #475569; font-style: italic;">
                        {{ $grade->notes ? '"' . $grade->notes . '"' : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center" style="padding: 20px; color: #64748b;">
                        Belum ada data rekap penilaian pembelajaran untuk siswa ini pada tahun ajaran aktif.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <table class="footer">
        <tr>
            <td style="width: 65%;">
                <p style="font-size: 10px; color: #64748b; margin-top: 15px;">
                    * Dokumen ini dibuat secara otomatis melalui Sistem SinergiEdu dan sah sebagai laporan hasil pembelajaran.
                </p>
            </td>
            <td style="width: 35%; text-align: right;">
                <div class="signature-box" style="float: right;">
                    <p style="margin-bottom: 4px;">Mengetahui,</p>
                    <p class="font-bold" style="margin-top: 0;">Waka Kurikulum</p>
                    <div class="signature-space"></div>
                    <p class="font-bold" style="border-bottom: 1px solid #334155; display: inline-block; padding-bottom: 2px; min-width: 160px;">
                        ( {{ auth()->user()->name ?? 'Waka Kurikulum' }} )
                    </p>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>

