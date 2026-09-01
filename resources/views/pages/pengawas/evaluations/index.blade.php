<x-layouts.app>
    <x-slot:title>Arsip Dokumen Evaluasi</x-slot:title>

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <x-page-header
                title="Arsip Evaluasi Sekolah"
                description="Semua dokumen evaluasi, supervisi akademis, dan catatan sekolah tersimpan di sini." />
            <x-button href="{{ route('pengawas.evaluations.create') }}" variant="primary">
                <svg class="w-4 h-4 mr-1.5 -ml-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tulis Evaluasi Baru
            </x-button>
        </div>

        {{-- Flash alert + link ke arsip --}}
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-start gap-3">
                <svg class="h-5 w-5 text-emerald-600 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <div>
                    <span class="font-semibold">{{ session('success') }}</span>
                    <p class="mt-0.5 text-emerald-700 text-xs">Dokumen Anda telah tersimpan dan dapat dilihat di daftar arsip di bawah ini.</p>
                </div>
            </div>
        @endif

        {{-- Search bar --}}
        <form method="GET" action="{{ route('pengawas.evaluations.index') }}" class="flex gap-2">
            <div class="relative flex-1 max-w-md">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari judul atau isi evaluasi…"
                    class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                />
            </div>
            <button type="submit"
                class="px-4 py-2.5 rounded-xl bg-slate-800 text-white text-sm font-semibold hover:bg-slate-900 transition">
                Cari
            </button>
            @if($search)
                <a href="{{ route('pengawas.evaluations.index') }}"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition">
                    Reset
                </a>
            @endif
        </form>

        {{-- Stats bar --}}
        <div class="flex items-center gap-3 text-sm text-slate-500">
            <span class="font-medium text-slate-700">{{ $evaluations->total() }} dokumen</span>
            @if($search)
                <span>· hasil pencarian untuk <span class="font-semibold text-slate-800">"{{ $search }}"</span></span>
            @endif
        </div>

        {{-- Document grid / list --}}
        @if($evaluations->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 px-6 py-16 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4 ring-8 ring-slate-50/50">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">
                    {{ $search ? 'Dokumen tidak ditemukan' : 'Arsip Masih Kosong' }}
                </h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    {{ $search ? 'Coba kata kunci yang berbeda atau hapus filter pencarian.' : 'Belum ada evaluasi yang dibuat. Klik tombol "Tulis Evaluasi Baru" untuk memulai.' }}
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4">
                @foreach($evaluations as $eval)
                    <div class="group bg-white rounded-2xl border border-slate-200 hover:border-blue-200 hover:shadow-md transition-all duration-200 overflow-hidden">
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            {{-- Color accent bar --}}
                            <div class="w-full sm:w-1.5 sm:h-auto h-1.5 bg-gradient-to-r sm:bg-gradient-to-b from-blue-500 to-indigo-500 flex-shrink-0"></div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0 px-5 py-4">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap mb-1">
                                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">
                                                {{ $eval->created_at->translatedFormat('d F Y') }}
                                            </span>
                                            @if($eval->updated_at->gt($eval->created_at))
                                                <span class="inline-flex items-center gap-1 text-xs text-amber-600 bg-amber-50 border border-amber-100 rounded-full px-2 py-0.5">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                    </svg>
                                                    Diperbarui
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="text-base font-bold text-slate-900 group-hover:text-blue-700 transition truncate">
                                            {{ $eval->title }}
                                        </h3>
                                        <p class="text-sm text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                            {{ $eval->content }}
                                        </p>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <a href="{{ route('pengawas.evaluations.show', $eval) }}"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-50 text-blue-700 text-xs font-semibold hover:bg-blue-100 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.58-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Lihat
                                        </a>
                                        <a href="{{ route('pengawas.evaluations.edit', $eval) }}"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-50 text-slate-600 text-xs font-semibold hover:bg-slate-100 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('pengawas.evaluations.destroy', $eval) }}" method="POST"
                                            onsubmit="return confirm('Hapus evaluasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($evaluations->hasPages())
                <div class="mt-2">
                    {{ $evaluations->links() }}
                </div>
            @endif
        @endif

    </div>
</x-layouts.app>

