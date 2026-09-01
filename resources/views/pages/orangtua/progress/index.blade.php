<x-layouts.app>
    <x-slot:title>Perkembangan Hasil Belajar Anak</x-slot:title>

    <div class="space-y-8">
        <x-page-header 
            title="Perkembangan Hasil Belajar Anak" 
            description="Pantau tren perkembangan belajar mingguan anak Anda dibandingkan dengan rata-rata kelas." 
        />

        <x-card padding="md" class="border border-slate-200">
            <form action="{{ route('orangtua.progress.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <x-input-label for="student_id" :value="__('Pilih Profil Anak')" />
                    <x-select id="student_id" name="student_id" onchange="this.form.submit()" class="mt-1 block w-full md:max-w-xs">
                        @foreach($children as $child)
                            <option value="{{ $child->id }}" {{ $selectedStudentId == $child->id ? 'selected' : '' }}>
                                {{ $child->user->name ?? 'Anak' }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $activeYear ? $activeYear->year : 'Tahun Ajaran' }} - {{ $activeSemester ? $activeSemester->name : 'Semester' }}
                    </span>
                </div>
            </form>
        </x-card>

        @if($selectedStudent)
            <div class="grid grid-cols-1 gap-8">
                <section class="space-y-6">
                    <h2 class="text-lg font-bold text-slate-900">Grafik & Komparasi Kinerja Belajar</h2>
                    @forelse($grades as $grade)
                        @php
                            $avgs = $classAverages[$grade->subject_id] ?? ['pre_test' => 0, 'assignment' => 0, 'post_test' => 0, 'character' => 0, 'memorization' => 0];
                        @endphp
                        <x-card padding="lg" class="border border-slate-200">
                            <div class="border-b border-slate-100 pb-4 mb-6">
                                <h3 class="text-base font-bold text-slate-900">{{ $grade->subject->name }}</h3>
                                <p class="text-xs text-slate-500">Guru: {{ $grade->teacher->user->name ?? '-' }}</p>
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
                        <x-card padding="lg" class="text-center bg-slate-50 border border-slate-200">
                            <p class="text-slate-500">Belum ada data nilai pada semester aktif ini.</p>
                        </x-card>
                    @endforelse
                </section>
            </div>
        @endif
    </div>
</x-layouts.app>
