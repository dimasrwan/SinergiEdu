<x-layouts.app>
    <x-slot:title>Nilai Anak</x-slot:title>

    <div class="space-y-8">
        <x-page-header 
            title="Nilai Anak" 
            description="Pantau ringkasan nilai dan pencapaian akademik anak Anda." 
        />

        <!-- Child Selector -->
        <div class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-sm">
            <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Anak yang Dipantau</h2>
            <form action="{{ route('orangtua.grades.index') }}" method="GET" class="w-full md:max-w-md">
                <div class="relative">
                    <select name="student_id" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-[15px] font-bold rounded-xl focus:ring-primary focus:border-primary block p-3 pr-10 appearance-none cursor-pointer hover:bg-slate-100 transition">
                        @foreach($children as $child)
                            <option value="{{ $child->id }}" {{ (int)$selectedStudentId === $child->id ? 'selected' : '' }}>
                                {{ $child->user->name ?? 'Anak' }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    </div>
                </div>
            </form>
        </div>

        @if($selectedStudent)
            @if($grades->isEmpty())
                <div class="bg-slate-50 border border-slate-200/75 rounded-2xl py-12 px-8 text-center shadow-sm max-w-3xl mx-auto w-full">
                    <div class="h-16 w-16 bg-white border border-slate-200 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Nilai</h3>
                    <p class="text-sm text-slate-500 font-medium">Guru belum mengunggah rekapitulasi nilai untuk anak Anda pada periode yang dipilih.</p>
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
            <div class="bg-slate-50 border border-slate-200/75 rounded-2xl py-12 px-8 text-center shadow-sm max-w-3xl mx-auto w-full">
                <div class="h-16 w-16 bg-white border border-slate-200 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Anak Terdaftar</h3>
                <p class="text-sm text-slate-500 font-medium">Anda belum memiliki anak yang terdaftar pada sistem sekolah ini.</p>
            </div>
        @endif
    </div>
</x-layouts.app>
