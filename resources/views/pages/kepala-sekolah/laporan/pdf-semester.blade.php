<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Semester - SinergiEdu</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif; }
        body { font-size: 12px; color: #1e293b; padding: 24px; }
        .header { text-align: center; border-bottom: 2px solid #1e3a8a; padding-bottom: 12px; margin-bottom: 24px; }
        .header h1 { margin: 0; font-size: 20px; color: #1e3a8a; }
        .header p { margin: 4px 0 0; color: #64748b; font-size: 12px; }
        .summary { display: flex; justify-content: space-between; gap: 12px; margin-bottom: 24px; }
        .box { border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 16px; flex: 1; text-align: center; }
        .box .label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; }
        .box .value { font-size: 20px; font-weight: bold; color: #1e3a8a; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        th { background: #f1f5f9; font-size: 11px; text-transform: uppercase; letter-spacing: 0.03em; }
        td { font-size: 11px; }
        .section-title { margin: 16px 0 8px; color: #1e3a8a; }
        .footer { text-align: center; margin-top: 32px; color: #94a3b8; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Rekap Semester</h1>
        <p>SinergiEdu — {{ config('app.name') }}</p>
    </div>

    <div class="summary">
        <div class="box">
            <div class="label">Rata-rata Sekolah</div>
            <div class="value">{{ $schoolAvgGrade }}</div>
        </div>
        <div class="box">
            <div class="label">Pretest</div>
            <div class="value">{{ $componentAverages['avg_pre_test'] }}</div>
        </div>
        <div class="box">
            <div class="label">Tugas</div>
            <div class="value">{{ $componentAverages['avg_assignment'] }}</div>
        </div>
        <div class="box">
            <div class="label">Posttest</div>
            <div class="value">{{ $componentAverages['avg_post_test'] }}</div>
        </div>
        <div class="box">
            <div class="label">Karakter</div>
            <div class="value">{{ $componentAverages['avg_character'] }}</div>
        </div>
        <div class="box">
            <div class="label">Hafalan</div>
            <div class="value">{{ $componentAverages['avg_memorization'] }}</div>
        </div>
    </div>

    <h2 class="section-title">Rangking Kelas</h2>
    <table>
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Kelas</th>
                <th>Tingkat</th>
                <th>Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @forelse($classRankings as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['grade_level'] }}</td>
                    <td>{{ $row['avg'] }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem SinergiEdu pada {{ now()->format('d M Y H:i') }}.</p>
    </div>
</body>
</html>