<!DOCTYPE html>
<html>
<head><style>body{font-family:sans-serif; font-size:12px;} table{width:100%; border-collapse:collapse;} th,td{border:1px solid #000; padding:4px;}</style></head>
<body>
<h1>Laporan Penilaian Klasikal</h1>
<p>Periode: {{ $activeYear->year ?? '-' }}</p>
<table>
    <thead><tr><th>Siswa</th><th>Kelas</th><th>Mapel</th><th>Rata-Rata</th></tr></thead>
    <tbody>
        @foreach($grades as $g)
        <tr><td>{{ $g->student->user->name }}</td><td>{{ $g->learningMeeting->classroom->name }}</td><td>{{ $g->learningMeeting->subject->name }}</td><td>{{ $g->average_score }}</td></tr>
        @endforeach
    </tbody>
</table>
</body>
</html>