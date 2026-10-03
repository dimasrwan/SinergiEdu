<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Hasil Belajar</title>
    <style>
        @page { size: a4 landscape; margin: 20px 25px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 25px; }
        th, td { border: 1px solid #e2e8f0; padding: 7px; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; color: #334155; text-transform: uppercase; font-size: 9.5px; text-align: center; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .footer { border-top: 1px solid #e2e8f0; padding-top: 10px; display: table; width: 100%; margin-top: 30px; }
        .footer-left { display: table-cell; text-align: left; color: #64748b; font-size: 10px; }
        .footer-right { display: table-cell; text-align: right; color: #64748b; font-size: 10px; }
    </style>
</head>
<body>

    @php
        // Try to fetch school name for Pengawas based on their active school
        $activeSchoolId = session('pengawas_school_id');
        $schoolName = 'Sekolah';
        if ($activeSchoolId) {
            $schoolName = \App\Models\School::find($activeSchoolId)?->name ?? 'Sekolah';
        }
        $academicYearName = $activeYear->year ?? '-';
        $semesterName = $activeSemester->name ?? '-';
    @endphp

    <x-pdf.header 
        title="LAPORAN HASIL BELAJAR SISWA" 
        :schoolName="$schoolName"
        :academicYear="$academicYearName"
        :semester="$semesterName"
    />

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 10%;">NIS</th>
                <th style="width: 10%;">NISN</th>
                <th style="width: 26%;">Nama Siswa</th>
                <th style="width: 8%;">Tes Awal</th>
                <th style="width: 8%;">Tugas</th>
                <th style="width: 8%;">Tes Akhir</th>
                <th style="width: 8%;">Karakter</th>
                <th style="width: 8%;">Hafalan</th>
                <th style="width: 10%;">Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($students as $student)
                @foreach($student->studentGrades as $grade)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $student->nis ?? '-' }}</td>
                        <td>{{ $student->nisn ?? '-' }}</td>
                        <td class="font-bold">{{ $student->user?->name ?? '-' }}</td>
                        <td class="text-center">{{ $grade->pre_test_score ?? 0 }}</td>
                        <td class="text-center">{{ $grade->assignment_score ?? 0 }}</td>
                        <td class="text-center">{{ $grade->post_test_score ?? 0 }}</td>
                        <td class="text-center">{{ $grade->character_score ?? 0 }}</td>
                        <td class="text-center">{{ $grade->memorization_score ?? 0 }}</td>
                        <td class="text-center font-bold" style="background-color: #f8fafc; color: #0284c7;">{{ $grade->average_score ?? 0 }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 20px; color: #64748b;">Tidak ada data hasil belajar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-left">SinergiEdu — Peduli Prosesnya, Tumbuh Hasilnya.</div>
        <div class="footer-right">Dicetak: {{ date('d/m/Y H:i') }}</div>
    </div>

</body>
</html>
