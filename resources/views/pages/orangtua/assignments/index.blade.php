<x-layouts.app>
    <x-slot:title>Daftar Tugas & PR Anak</x-slot:title>

    <div class="space-y-8">
        <x-page-header 
            title="Daftar Tugas & PR Anak" 
            description="Pantau tugas awal, materi, dan PR yang diberikan guru serta status pengerjaannya." 
        />

        <x-card padding="md" class="border border-slate-200">
            <form action="{{ route('orangtua.assignments.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
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
            <div class="space-y-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <x-card padding="sm" class="border border-slate-200">
                        <div class="text-sm text-slate-500 font-medium">Total Tugas</div>
                        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total_tugas'] ?? 0 }}</div>
                    </x-card>
                    <x-card padding="sm" class="border border-slate-200">
                        <div class="text-sm text-slate-500 font-medium">Sudah Dikumpulkan</div>
                        <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['selesai'] ?? 0 }}</div>
                    </x-card>
                    <x-card padding="sm" class="border border-slate-200">
                        <div class="text-sm text-slate-500 font-medium">Menunggu Penilaian</div>
                        <div class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['menunggu_penilaian'] ?? 0 }}</div>
                    </x-card>
                    <x-card padding="sm" class="border border-slate-200">
                        <div class="text-sm text-slate-500 font-medium">Belum Dikumpulkan</div>
                        <div class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['belum_dikumpulkan'] ?? 0 }}</div>
                    </x-card>
                </div>

                <h2 class="text-lg font-bold text-slate-900">Daftar Tugas di Kelas {{ $selectedStudent->classes()->first()?->name ?? '-' }}</h2>

                <div class="grid grid-cols-1 gap-4">
                    @forelse($assignments as $assignment)
                        @php
                            $submission = $assignment->submissions->first();
                            $isDeadlinePassed = $assignment->deadline && $assignment->deadline->isPast();
                            
                            $statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">Belum Mengumpulkan</span>';
                            
                            if ($submission) {
                                $isLate = $assignment->deadline && $submission->submitted_at && \Carbon\Carbon::parse($submission->submitted_at)->gt($assignment->deadline);
                                
                                if ($submission->score !== null) {
                                    $statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Dinilai</span>';
                                } else {
                                    $statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">Menunggu Penilaian</span>';
                                }
                                
                                if ($isLate) {
                                    $statusBadge .= ' <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-50 text-orange-700 border border-orange-200 ml-1">Terlambat</span>';
                                }
                            } elseif ($isDeadlinePassed) {
                                $statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">Terlewat</span>';
                            }
                        @endphp
                        <x-card padding="none" class="overflow-hidden border border-slate-200 hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $assignment->subject->name }}
                                            </span>
                                            {!! $statusBadge !!}
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $assignment->title }}</h3>
                                        <p class="text-sm text-slate-500 mb-3">{{ Str::limit($assignment->description, 100) }}</p>
                                        <div class="flex items-center gap-x-4 text-xs text-slate-500">
                                            <span>Guru: {{ $assignment->teacher->user->name ?? '-' }}</span>
                                            <span>Deadline: {{ $assignment->deadline ? $assignment->deadline->format('d M Y') : '-' }}</span>
                                        </div>
                                    </div>
                                    @if($submission && $submission->score)
                                        <div class="flex flex-col items-center justify-center p-3 bg-slate-50 rounded-xl min-w-[80px] border border-slate-100">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase">Nilai</span>
                                            <span class="text-xl font-black text-primary">{{ $submission->score }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </x-card>
                    @empty
                        <div class="text-center bg-slate-50 border border-slate-200 py-10 rounded-xl">
                            <p class="text-slate-500">Belum ada tugas yang diberikan.</p>
                        </div>
                    @endforelse
                    @if(method_exists($assignments, 'links'))
                        <div class="mt-4">{{ $assignments->links() }}</div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
