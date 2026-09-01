<x-layouts.app>
    <x-slot:title>Detail Siswa – {{ $student->user?->name }}</x-slot:title>

    <div class="space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('pengawas.students.index') }}" class="hover:text-slate-800 font-semibold transition flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                Monitoring Siswa
            </a>
            <span>/</span>
            <span class="text-slate-800 font-semibold">{{ $student->user?->name }}</span>
        </div>

        {{-- Hero Card Profil Siswa --}}
        <div class="bg-gradient-to-br from-primary to-cyan-500 rounded-3xl p-6 text-white shadow-xl shadow-primary/20 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-56 h-56 bg-white/5 rounded-full blur-3xl -translate-y-1/3 translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center gap-5">
                <div class="h-20 w-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl font-black shrink-0">
                    {{ strtoupper(substr($student->user?->name ?? '?', 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold">{{ $student->user?->name }}</h1>
                    <div class="flex flex-wrap gap-x-6 gap-y-1 mt-2 text-blue-100 text-sm">
                        <span>NIS: <strong class="text-white">{{ $student->nis ?? '-' }}</strong></span>
                        <span>NISN: <strong class="text-white">{{ $student->nisn ?? '-' }}</strong></span>
                        <span>{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="flex flex-wrap gap-x-6 gap-y-1 mt-1 text-blue-100 text-sm">
                        <span>Orang Tua: <strong class="text-white">{{ $student->parent?->user?->name ?? '-' }}</strong></span>
                        @if($activeClassroom)
                            <span>Kelas: <strong class="text-white">{{ $activeClassroom->name }}</strong></span>
                        @endif
                    </div>
                </div>
                @if($activeYear)
                    <div class="text-right shrink-0">
                        <div class="text-xs text-blue-200">Tahun Ajaran Aktif</div>
                        <div class="text-sm font-bold">{{ $activeYear->name }}</div>
                        @if($activeSemester)
                            <div class="text-xs text-blue-200 mt-0.5">{{ $activeSemester->name }}</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Kiri: Tabel Nilai + Grafik --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- Tabel Nilai Komponen Semester Aktif --}}
                <x-card padding="none">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-base font-bold text-slate-900">Nilai Komponen Belajar</h2>
                        @if($activeSemester)
                            <span class="text-xs text-slate-500 bg-slate-100 px-3 py-1 rounded-lg">{{ $activeYear?->name }} – {{ $activeSemester?->name }}</span>
                        @endif
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Mata Pelajaran</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wide">Tes Awal</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wide">Tugas</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wide">Tes Akhir</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wide">Karakter</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wide">Hafalan</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wide">Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($grades as $grade)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-5 py-3.5">
                                            <div class="font-semibold text-slate-800">{{ $grade->subject?->name ?? '-' }}</div>
                                            <div class="text-xs text-slate-400">{{ $grade->teacher?->user?->name ?? '' }}</div>
                                        </td>
                                        @foreach(['pre_test_score','assignment_score','post_test_score','character_score','memorization_score'] as $col)
                                            <td class="px-4 py-3.5 text-center">
                                                @if(!is_null($grade->$col))
                                                    <span class="font-bold {{ $grade->$col >= 75 ? 'text-emerald-600' : ($grade->$col >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                                                        {{ $grade->$col }}
                                                    </span>
                                                @else
                                                    <span class="text-slate-300">–</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="px-4 py-3.5 text-center">
                                            <span class="font-black text-slate-900 text-base">{{ $grade->average_score > 0 ? $grade->average_score : '–' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-5 py-10 text-center text-slate-400 text-sm">Belum ada data nilai untuk semester ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>

                {{-- Grafik Perkembangan --}}
                @if($chartData->isNotEmpty())
                <x-card padding="md">
                    <h2 class="text-base font-bold text-slate-900 mb-4">Grafik Perkembangan Nilai</h2>
                    <div class="relative w-full" style="height: 260px;">
                        <canvas id="studentProgressChart"></canvas>
                    </div>
                </x-card>
                @endif

                {{-- Feedback dari Guru --}}
                <x-card padding="none">
                    <div class="p-5 border-b border-slate-100">
                        <h2 class="text-base font-bold text-slate-900">Umpan Balik dari Guru</h2>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($teacherFeedbacks as $fb)
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-slate-800 text-sm">{{ $fb->title }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">{{ $fb->teacher?->user?->name ?? '-' }} · {{ $fb->subject?->name ?? '-' }}</div>
                                        <p class="text-sm text-slate-600 mt-2 leading-relaxed">{{ $fb->message }}</p>
                                    </div>
                                    <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $fb->type_color }}">
                                        {{ $fb->type_label }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-400 mt-2">{{ $fb->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        @empty
                            <div class="p-10 text-center text-slate-400 text-sm">Belum ada umpan balik dari guru.</div>
                        @endforelse
                    </div>
                </x-card>
            </div>

            {{-- Kanan: Form Feedback Pengawas + Riwayat --}}
            <div class="space-y-6">
                {{-- Form Tulis Feedback --}}
                <x-card padding="md">
                    <h2 class="text-base font-bold text-slate-900 mb-4">Tulis Umpan Balik</h2>
                    <form action="{{ route('pengawas.students.feedback.store', $student) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="academic_year_id" value="{{ $activeYear?->id }}">
                        <input type="hidden" name="semester_id" value="{{ $activeSemester?->id }}">
                        <input type="hidden" name="class_id" value="{{ $activeClassroom?->id }}">

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jenis Umpan Balik</label>
                            <div class="flex gap-2">
                                @foreach(['positive' => ['label' => 'Positif', 'color' => 'text-emerald-700 border-emerald-200 bg-emerald-50'], 'neutral' => ['label' => 'Netral', 'color' => 'text-slate-600 border-slate-200 bg-slate-50'], 'negative' => ['label' => 'Negatif', 'color' => 'text-red-700 border-red-200 bg-red-50']] as $val => $meta)
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="type" value="{{ $val }}" class="sr-only peer" {{ $val === 'neutral' ? 'checked' : '' }}>
                                        <span class="block text-center text-xs font-semibold px-2 py-2 rounded-lg border peer-checked:ring-2 peer-checked:ring-offset-1 {{ $meta['color'] }} transition peer-checked:ring-current">
                                            {{ $meta['label'] }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="fb_content" class="block text-xs font-semibold text-slate-600 mb-1.5">Isi Umpan Balik</label>
                            <textarea id="fb_content" name="content" rows="5" required
                                placeholder="Tuliskan umpan balik, catatan pengawasan, atau rekomendasi untuk siswa ini..."
                                class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-cyan-500 focus:bg-white transition resize-none">{{ old('content') }}</textarea>
                            @error('content') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-xl transition shadow-sm">
                            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg>
                            Kirim Umpan Balik
                        </button>
                    </form>
                </x-card>

                {{-- Riwayat Feedback Pengawas --}}
                <x-card padding="none">
                    <div class="p-5 border-b border-slate-100">
                        <h2 class="text-base font-bold text-slate-900">Umpan Balik Saya</h2>
                    </div>
                    <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto">
                        @forelse($pengawasFeedbacks as $fb)
                            <div class="p-4">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-sm text-slate-700 leading-relaxed flex-1">{{ $fb->content }}</p>
                                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold {{ $fb->type_badge_class }}">
                                        {{ $fb->type_label }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-xs text-slate-400">{{ $fb->created_at->format('d M Y') }}</span>
                                    <form action="{{ route('pengawas.feedback.destroy', $fb) }}" method="POST" onsubmit="return confirm('Hapus umpan balik ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 text-sm">Belum ada umpan balik.</div>
                        @endforelse
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    @if($chartData->isNotEmpty())
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);
            const labels = chartData.map(d => d.label);

            new Chart(document.getElementById('studentProgressChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        { label: 'Tes Awal',  data: chartData.map(d => d.pre_test),     borderColor: '#123B82', backgroundColor: 'rgba(18,59,130,0.1)',  tension: 0.4, fill: false },
                        { label: 'Tes Akhir', data: chartData.map(d => d.post_test),    borderColor: '#119FEA', backgroundColor: 'rgba(17,159,234,0.1)', tension: 0.4, fill: false },
                        { label: 'Tugas',     data: chartData.map(d => d.assignment),   borderColor: '#10B981', backgroundColor: 'rgba(16,185,129,0.1)', tension: 0.4, fill: false },
                        { label: 'Karakter',  data: chartData.map(d => d.character),    borderColor: '#F59E0B', backgroundColor: 'rgba(245,158,11,0.1)',  tension: 0.4, fill: false },
                        { label: 'Hafalan',   data: chartData.map(d => d.memorization), borderColor: '#8B5CF6', backgroundColor: 'rgba(139,92,246,0.1)',  tension: 0.4, fill: false },
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, max: 100 } },
                    plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } } }
                }
            });
        });
    </script>
    @endpush
    @endif
</x-layouts.app>
