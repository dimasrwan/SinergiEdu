@props(['title', 'schoolName' => null, 'academicYear' => null, 'semester' => null])

@php
    $logoPath = public_path('images/logo.png'); // Using PNG if exists, fallback to SVG if PNG doesn't exist
    if (!file_exists($logoPath)) {
        $logoPath = public_path('images/logo.svg');
    }
    
    // For DomPDF, it's often safer to use base64 if paths are tricky, but public_path() usually works.
    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
    $data = file_exists($logoPath) ? file_get_contents($logoPath) : '';
    $base64 = $data ? 'data:image/' . $type . ';base64,' . base64_encode($data) : '';
@endphp

<div style="border-bottom: 2px solid #119FEA; padding-bottom: 15px; margin-bottom: 25px; display: table; width: 100%;">
    <div style="display: table-cell; width: 60px; vertical-align: middle;">
        @if($base64)
            <img src="{{ $base64 }}" alt="Logo" style="height: 50px; width: auto; object-fit: contain;">
        @else
            <!-- Fallback if logo not found -->
            <div style="height: 50px; width: 50px; background-color: #123B82; color: white; text-align: center; line-height: 50px; font-weight: bold; border-radius: 8px;">SE</div>
        @endif
    </div>
    
    <div style="display: table-cell; vertical-align: middle; text-align: center;">
        <h1 style="margin: 0 0 2px 0; color: #123B82; font-size: 22px; font-weight: bold; letter-spacing: 0.5px;">SINERGIEDU</h1>
        <p style="margin: 0; color: #64748b; font-size: 11px; letter-spacing: 0.2px;">Integrated Education Management Platform</p>
    </div>
</div>

<div style="text-align: center; margin-bottom: 20px;">
    <h2 style="margin: 0 0 5px 0; color: #123B82; font-size: 16px; font-weight: bold; text-transform: uppercase;">{{ $title }}</h2>
    
    @if($schoolName || $academicYear || $semester)
        <p style="margin: 0; color: #475569; font-size: 11px;">
            @if($schoolName)
                <strong>{{ $schoolName }}</strong><br>
            @endif
            @if($academicYear || $semester)
                Tahun Ajaran {{ $academicYear ?? '-' }} &bull; Semester {{ $semester ?? '-' }}
            @endif
        </p>
    @endif
</div>
