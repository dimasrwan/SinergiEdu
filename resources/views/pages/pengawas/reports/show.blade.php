<x-layouts.app>
    <x-slot:title>Laporan Kinerja – {{ $report->title }}</x-slot:title>

    <div class="space-y-6">
        <div class="flex items-center justify-between no-print gap-3">
            <a href="{{ route('pengawas.reports.index') }}"
                class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <button onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold text-white bg-primary hover:bg-blue-800 rounded-lg transition shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.72 19.164h10.56M16.5 10.125V3h-9v7.125m9 0v8.623a.75.75 0 0 1-.75.75h-7.5a.75.75 0 0 1-.75-.75V10.125m9 0h2.25a.75.75 0 0 1 .75.75v5.25a.75.75 0 0 1-.75.75M7.5 10.125H5.25a.75.75 0 0 0-.75.75v5.25a.75.75 0 0 0 .75.75" />
                </svg>
                Cetak Laporan (PDF)
            </button>
        </div>

        {{-- Kertas Laporan --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-8 max-w-4xl mx-auto shadow-sm print:shadow-none print:border-none print:p-0"
            id="report-paper">
            {{-- Header Surat/Laporan --}}
            <div class="text-center border-b-4 border-double border-slate-900 pb-5 mb-6">
                <h2 class="text-xl font-black uppercase tracking-wide text-slate-900">SinergiEdu</h2>
                <p class="text-xs text-slate-500 uppercase tracking-widest mt-0.5">Sistem Informasi Pengawasan &
                    Evaluasi Sekolah</p>
                <div class="text-[10px] text-slate-400 mt-1">Lembaga Penjaminan Mutu Pendidikan SinergiEdu</div>
            </div>

            {{-- Info Dokumen --}}
            <div
                class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm bg-slate-50 p-4 rounded-2xl border border-slate-100 print:bg-transparent print:border-none print:p-0 print:grid-cols-2 mb-6">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Pembuat Laporan:</span>
                    <p class="font-bold text-slate-900 mt-0.5">{{ $report->pengawas?->name ?? 'Pengawas Sekolah' }}</p>
                    <p class="text-xs text-slate-500">Jabatan: Pengawas Sekolah/Madrasah</p>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Detail Target
                        Laporan:</span>
                    @if($report->teacher)
                        <p class="font-bold text-slate-900 mt-0.5">Guru: {{ $report->teacher->user?->name }}</p>
                        @if($report->teacher->nip)
                            <p class="text-xs text-slate-500">NIP: {{ $report->teacher->nip }}</p>
                        @endif
                    @endif
                    @if($report->classroom)
                        <p class="font-bold text-slate-900 mt-0.5">Kelas: {{ $report->classroom->name }}</p>
                    @endif
                    @if(!$report->teacher && !$report->classroom)
                        <p class="font-bold text-slate-900 mt-0.5">Seluruh Sekolah</p>
                    @endif
                </div>
                <div class="sm:col-span-2 pt-2 border-t border-slate-200/50 print:border-none">
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Tanggal Dokumen:</span>
                    <p class="font-semibold text-slate-800 mt-0.5">{{ $report->created_at->format('d F Y, H:i') }}</p>
                </div>
            </div>

            {{-- Judul Laporan --}}
            <div class="mb-5 text-center">
                <h1 class="text-lg font-black uppercase text-slate-900 decoration-slate-900 decoration-1">
                    {{ $report->title }}</h1>
            </div>

            {{-- Isi Laporan --}}
            <div class="prose max-w-none text-slate-800 text-sm leading-relaxed space-y-4 mb-6">
                <div>
                    <h3 class="font-bold text-slate-900 border-b border-slate-200 pb-1.5 mb-2.5">I. HASIL EVALUASI &
                        TEMUAN KINERJA</h3>
                    <p class="whitespace-pre-line text-justify">{{ $report->content }}</p>
                </div>

                @if($report->recommendations)
                    <div class="pt-4">
                        <h3 class="font-bold text-slate-900 border-b border-slate-200 pb-1.5 mb-2.5">II. REKOMENDASI TINDAK
                            LANJUT</h3>
                        <p class="whitespace-pre-line text-justify italic">"{{ $report->recommendations }}"</p>
                    </div>
                @endif
            </div>

            {{-- Tanda Tangan --}}
            <div class="mt-12 flex justify-end">
                <div class="text-center w-64">
                    <p class="text-xs text-slate-500">Jakarta, {{ $report->created_at->format('d F Y') }}</p>
                    <p class="text-xs font-bold text-slate-700 mt-0.5">Pengawas Sekolah/Madrasah,</p>
                    <div class="h-16"></div>
                    <p class="font-bold text-slate-900 border-b border-slate-900 pb-1 inline-block min-w-44">
                        {{ $report->pengawas?->name ?? 'Pengawas' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS Khusus Cetak --}}
    @push('styles')
        <style>
            @media print {
                body {
                    background: white !important;
                    color: black !important;
                }

                .no-print {
                    display: none !important;
                }

                header,
                sidebar,
                nav,
                aside,
                footer {
                    display: none !important;
                }

                main {
                    padding: 0 !important;
                    margin: 0 !important;
                    width: 100% !important;
                }

                #report-paper {
                    border: none !important;
                    box-shadow: none !important;
                    padding: 0 !important;
                    max-width: 100% !important;
                    margin: 0 !important;
                }
            }
        </style>
    @endpush
</x-layouts.app>