<x-layouts.app>
    <x-slot:title>Berikan Feedback - {{ $student->user?->name }}</x-slot:title>

    <div class="space-y-6">
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('pengawas.students.show', $student->id) }}" class="hover:text-slate-800 font-semibold transition flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                {{ $student->user?->name }}
            </a>
            <span>/</span>
            <span class="text-slate-800 font-semibold">Berikan Feedback</span>
        </div>

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Berikan Feedback kepada Siswa</h1>
            <p class="text-sm text-slate-500 mt-1">Feedback akan ditampilkan kepada siswa dan orang tua untuk membantu meningkatkan hasil belajar</p>
        </div>

        {{-- Info Siswa --}}
        <x-card padding="md" class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200">
            <div class="flex items-center gap-4">
                <div class="h-16 w-16 rounded-full bg-blue-200 flex items-center justify-center text-xl font-bold text-blue-700">
                    {{ strtoupper(substr($student->user?->name ?? '?', 0, 2)) }}
                </div>
                <div>
                    <p class="font-semibold text-slate-900">{{ $student->user?->name }}</p>
                    <p class="text-sm text-slate-600">NIS: {{ $student->nis }} | NISN: {{ $student->nisn }}</p>
                    <p class="text-xs text-slate-500 mt-1">Rata-rata Nilai: 
                        <span class="font-semibold text-blue-600">
                            {{ number_format($grades->avg('average_score') ?? 0, 1) }}
                        </span>
                    </p>
                </div>
            </div>
        </x-card>

        {{-- Form Feedback --}}
        <x-card padding="md">
            <form method="POST" action="{{ route('pengawas.feedback.store') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="student_id" value="{{ $student->id }}">

                {{-- Feedback Text --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">
                        Feedback untuk Siswa <span class="text-red-500">*</span>
                    </label>
                    <textarea name="feedback_text" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none" 
                              rows="5" 
                              placeholder="Berikan feedback yang konstruktif dan mendorong siswa untuk terus berkembang..."
                              required>{{ old('feedback_text') }}</textarea>
                    @error('feedback_text')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-2">Minimum 10 karakter, maksimal 1000 karakter</p>
                </div>

                {{-- Rencana Aksi --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">
                        Rencana Aksi untuk Peningkatan (Opsional)
                    </label>
                    <textarea name="action_plan" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none" 
                              rows="4" 
                              placeholder="Tuliskan langkah-langkah konkret yang dapat diambil oleh siswa, guru, orang tua, dan Anda sebagai pengawas...">{{ old('action_plan') }}</textarea>
                    @error('action_plan')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Prioritas --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">
                        Prioritas Rencana Aksi (Opsional)
                    </label>
                    <select name="priority" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="medium" selected>Prioritas Sedang (Default)</option>
                        <option value="low">Prioritas Rendah</option>
                        <option value="high">Prioritas Tinggi</option>
                    </select>
                </div>

                {{-- Tabel Nilai Referensi --}}
                <div class="bg-slate-50 p-4 rounded-lg">
                    <p class="text-xs font-semibold text-slate-600 uppercase mb-3">Nilai Saat Ini</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div class="bg-white p-3 rounded border border-slate-200">
                            <p class="text-xs text-slate-600">Rata-rata</p>
                            <p class="text-lg font-bold text-slate-900">{{ number_format($grades->avg('average_score') ?? 0, 1) }}</p>
                        </div>
                        <div class="bg-white p-3 rounded border border-slate-200">
                            <p class="text-xs text-slate-600">Tes Akhir</p>
                            <p class="text-lg font-bold text-slate-900">{{ number_format($grades->avg('post_test_score') ?? 0, 1) }}</p>
                        </div>
                        <div class="bg-white p-3 rounded border border-slate-200">
                            <p class="text-xs text-slate-600">Karakter</p>
                            <p class="text-lg font-bold text-slate-900">{{ number_format($grades->avg('character_score') ?? 0, 1) }}</p>
                        </div>
                        <div class="bg-white p-3 rounded border border-slate-200">
                            <p class="text-xs text-slate-600">Tugas</p>
                            <p class="text-lg font-bold text-slate-900">{{ number_format($grades->avg('assignment_score') ?? 0, 1) }}</p>
                        </div>
                        <div class="bg-white p-3 rounded border border-slate-200">
                            <p class="text-xs text-slate-600">Tes Awal</p>
                            <p class="text-lg font-bold text-slate-900">{{ number_format($grades->avg('pre_test_score') ?? 0, 1) }}</p>
                        </div>
                        <div class="bg-white p-3 rounded border border-slate-200">
                            <p class="text-xs text-slate-600">Hafalan</p>
                            <p class="text-lg font-bold text-slate-900">{{ number_format($grades->avg('memorization_score') ?? 0, 1) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3 pt-4 border-t border-slate-200">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-semibold">
                        Kirim Feedback
                    </button>
                    <a href="{{ route('pengawas.students.show', $student->id) }}" class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition font-semibold">
                        Batal
                    </a>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
