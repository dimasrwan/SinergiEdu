<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Bulanan - SinergiEdu</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif; }
        body { font-size: 12px; color: #1e293b; padding: 24px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        th { background: #f1f5f9; font-size: 11px; text-transform: uppercase; letter-spacing: 0.03em; }
        td { font-size: 11px; }
        .section-title { margin: 16px 0 8px; color: #1e3a8a; }
        .footer { text-align: center; margin-top: 32px; color: #94a3b8; font-size: 10px; }
    </style>
</head>
<body>
    @php
        $schoolName = auth()->user()->school->name ?? 'Sekolah';
    @endphp
    <x-pdf.header 
        title="LAPORAN REKAP BULANAN" 
        :schoolName="$schoolName" 
    />

    <h2 class="section-title">Analisis Mata Pelajaran</h2>
    <table>
        <thead>
            <tr>
                <th>Mata Pelajaran</th>
                <th>Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subjectAnalysis as $row)
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['avg'] }}</td>
                </tr>
            @empty
                <tr><td colspan="2">Belum ada data mata pelajaran.</td></tr>
            @endforelse
        </tbody>
    </table>

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
                <tr><td colspan="4">Belum ada data bulanan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer" style="border-top: 1px solid #e2e8f0; padding-top: 10px; display: table; width: 100%;">
        <div style="display: table-cell; text-align: left; color: #64748b; font-size: 10px;">
            SinergiEdu — Peduli Prosesnya, Tumbuh Hasilnya.
        </div>
        <div style="display: table-cell; text-align: right; color: #64748b; font-size: 10px;">
            Dicetak: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>
