<x-layouts.app>
    <x-slot:title>Nilai Anak</x-slot:title>

    <div class="space-y-8">
        <x-page-header 
            title="Nilai Anak" 
            description="Pantau ringkasan nilai dan pencapaian akademik anak Anda." 
        />

        <x-card padding="md" class="border border-slate-200">
            <form action="{{ route('orangtua.grades.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
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
            </form>
        </x-card>

        @if($selectedStudent)
            @if($grades->isEmpty())
                <div class="text-center bg-slate-50 border border-slate-200 py-10 rounded-xl">
                    <p class="text-slate-500">Belum Ada Nilai</p>
                </div>
            @else
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <x-card padding="sm" class="border border-slate-200">
                            <div class="text-sm text-slate-500 font-medium">Rata-rata Nilai</div>
                            <div class="text-2xl font-bold {{ $stats['rata_rata'] >= 80 ? 'text-emerald-600' : ($stats['rata_rata'] >= 60 ? 'text-amber-600' : 'text-slate-800') }} mt-1">
                                {{ $stats['rata_rata'] ?? '-' }}
                            </div>
                        </x-card>
                        <x-card padding="sm" class="border border-slate-200">
                            <div class="text-sm text-slate-500 font-medium">Mata Pelajaran</div>
                            <div class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['jumlah_mapel'] }}</div>
                        </x-card>
                        <x-card padding="sm" class="border border-slate-200">
                            <div class="text-sm text-slate-500 font-medium">Tugas Dinilai</div>
                            <div class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['tugas_dinilai'] }}</div>
                        </x-card>
                    </div>

                    <h2 class="text-lg font-bold text-slate-900">Nilai Mata Pelajaran</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($grades as $grade)
                            @php
                                $avg = $grade->average_score;
                            @endphp
                            <x-card padding="none" class="overflow-hidden border border-slate-200 hover:shadow-md transition-shadow">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <h3 class="text-lg font-bold text-slate-900">{{ $grade->subject->name ?? '-' }}</h3>
                                            <p class="text-sm text-slate-500">Guru: {{ $grade->teacher->user->name ?? '-' }}</p>
                                        </div>
                                        <div class="flex flex-col items-center justify-center p-3 {{ $avg >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : ($avg >= 60 ? 'bg-amber-50 text-amber-700 border-amber-100' : ($avg > 0 ? 'bg-red-50 text-red-700 border-red-100' : 'bg-slate-50 text-slate-500 border-slate-100')) }} rounded-xl min-w-[80px] border">
                                            <span class="text-[10px] font-bold uppercase opacity-70">Rata-rata</span>
                                            <span class="text-xl font-black">{{ $avg > 0 ? $avg : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between mt-4">
                                        <div class="text-sm text-slate-500">
                                            Tugas Dinilai: <span class="font-bold text-slate-700">{{ $grade->tugas_dinilai_text }}</span>
                                        </div>
                                        <a href="{{ route('orangtua.grades.show', $grade->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <div class="text-center bg-slate-50 border border-slate-200 py-10 rounded-xl">
                <p class="text-slate-500">Belum Ada Anak Terdaftar</p>
            </div>
        @endif
    </div>
</x-layouts.app>
