<x-layouts.app>
    <x-slot:title>Perkembangan Hasil Belajar Anak</x-slot:title>

    <div class="space-y-8">
        <x-page-header 
            title="Perkembangan Anak" 
            description="Pantau tren perkembangan belajar mingguan anak Anda dibandingkan dengan rata-rata kelas." 
        />

        <!-- Child Selector -->
        <div class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-sm">
            <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Anak yang Dipantau</h2>
            <form action="{{ route('orangtua.progress.index') }}" method="GET" class="w-full md:max-w-md">
                <x-select name="student_id" onchange="this.form.submit()" :selected="$selectedStudentId" :options="$children->map(fn($c) => ['value' => $c->id, 'label' => $c->user->name ?? 'Anak'])->toArray()" />
                <p class="text-[13px] text-slate-500 font-medium mt-2.5 ml-1">
                    {{ $activeYear ? $activeYear->year : 'Tahun Ajaran' }} &bull; {{ $activeSemester ? $activeSemester->name : 'Semester' }}
                </p>
            </form>
        </div>

        @if($selectedStudent)
            <div class="grid grid-cols-1 gap-8">
                <section class="space-y-6">
                    <h2 class="text-lg font-bold text-slate-900">Grafik & Komparasi Kinerja Belajar</h2>
                    @forelse($grades as $grade)
                        @php
                            $avgs = $classAverages[$grade->subject_id] ?? ['pre_test' => 0, 'assignment' => 0, 'post_test' => 0, 'character' => 0, 'memorization' => 0];
                        @endphp
                        <x-card padding="lg" class="border border-slate-200">
                            <div class="border-b border-slate-100 pb-4 mb-6 flex flex-col md:flex-row md:items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">{{ $grade->subject->name }}</h3>
                                    <p class="text-xs text-slate-500">Guru: {{ $grade->teacher->user->name ?? '-' }}</p>
                                </div>
                                <div class="md:text-right">
                                    <div class="text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Progress Tugas</div>
                                    @php
                                        $progress = $assignmentProgress[$grade->subject_id] ?? ['total' => 0, 'submitted' => 0];
                                    @endphp
                                    @if($progress['total'] > 0)
                                        <div class="flex items-center md:justify-end gap-3">
                                            <div class="w-24 bg-slate-100 rounded-full h-2 border border-slate-200 overflow-hidden">
                                                <div class="bg-primary h-full rounded-full" style="width: {{ ($progress['submitted'] / $progress['total']) * 100 }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold text-slate-700">{{ $progress['submitted'] }} / {{ $progress['total'] }} Selesai</span>
                                        </div>
                                    @else
                                        <span class="text-xs font-medium text-slate-400 italic">Belum Ada Tugas</span>
                                    @endif
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                                @include('pages.orangtua.progress._chart_item', ['label' => 'Tes Awal', 'score' => $grade->pre_test_score, 'avg' => $avgs['pre_test'], 'color' => 'bg-blue-500'])
                                @include('pages.orangtua.progress._chart_item', ['label' => 'Tugas', 'score' => $grade->assignment_score, 'avg' => $avgs['assignment'], 'color' => 'bg-indigo-500'])
                                @include('pages.orangtua.progress._chart_item', ['label' => 'Tes Akhir', 'score' => $grade->post_test_score, 'avg' => $avgs['post_test'], 'color' => 'bg-emerald-500'])
                                @include('pages.orangtua.progress._chart_item', ['label' => 'Karakter', 'score' => $grade->character_score, 'avg' => $avgs['character'], 'color' => 'bg-amber-500'])
                                @include('pages.orangtua.progress._chart_item', ['label' => 'Hafalan', 'score' => $grade->memorization_score, 'avg' => $avgs['memorization'], 'color' => 'bg-rose-500'])
                            </div>
                        </x-card>
                    @empty
                        <div class="bg-slate-50 border border-slate-200/75 rounded-2xl py-12 px-8 text-center shadow-sm max-w-3xl mx-auto w-full">
                            <div class="h-16 w-16 bg-white border border-slate-200 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Data Perkembangan</h3>
                            <p class="text-sm text-slate-500 font-medium">Belum tersedia data nilai pada semester aktif. Perkembangan anak akan muncul setelah guru menginput nilai.</p>
                            <div class="mt-6 inline-flex flex-col sm:flex-row items-center gap-3 text-xs text-slate-500 font-medium bg-white px-5 py-3 rounded-xl border border-slate-200">
                                <span class="font-bold text-slate-700">Yang akan dipantau:</span>
                                <span class="hidden sm:inline-block">&bull;</span>
                                <span>Rata-rata nilai</span>
                                <span class="hidden sm:inline-block">&bull;</span>
                                <span>Tren perkembangan</span>
                                <span class="hidden sm:inline-block">&bull;</span>
                                <span>Perbandingan dengan kelas</span>
                            </div>
                        </div>
                    @endforelse
                </section>
            </div>
        @endif
    </div>
</x-layouts.app>
