<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penilaian Klasikal</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; margin: 0; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 25px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; color: #334155; text-transform: uppercase; font-size: 10px; }
        .text-center { text-align: center; }
        .footer { border-top: 1px solid #e2e8f0; padding-top: 10px; display: table; width: 100%; margin-top: 30px; }
        .footer-left { display: table-cell; text-align: left; color: #64748b; font-size: 10px; }
        .footer-right { display: table-cell; text-align: right; color: #64748b; font-size: 10px; }
    </style>
</head>
<body>

    @php
        $schoolName = auth()->user()->school->name ?? 'Sekolah';
        $academicYearName = $activeYear->year ?? '-';
    @endphp

    <x-pdf.header 
        title="LAPORAN PENILAIAN KLASIKAL" 
        :schoolName="$schoolName"
        :academicYear="$academicYearName"
    />

    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 25%;">Siswa</th>
                <th style="width: 15%;">Kelas</th>
                <th style="width: 40%;">Mata Pelajaran</th>
                <th style="width: 15%;" class="text-center">Rata-Rata</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grades as $g)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $g->student->user->name ?? '-' }}</td>
                    <td>{{ $g->learningMeeting->classroom->name ?? '-' }}</td>
                    <td>{{ $g->learningMeeting->subject->name ?? '-' }}</td>
                    <td class="text-center font-bold">{{ $g->average_score }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-left">SinergiEdu — Peduli Prosesnya, Tumbuh Hasilnya.</div>
        <div class="footer-right">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

</body>
</html>