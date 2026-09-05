<x-layouts.app>
    <x-slot:title>Feedback Anak</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Feedback Guru" description="Lihat umpan balik yang diberikan guru untuk perkembangan anak Anda." />

        <!-- Child Selector -->
        <div class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-sm">
            <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Anak yang Dipantau</h2>
            <form action="{{ route('orangtua.feedbacks.index') }}" method="GET" class="w-full">
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <div class="relative w-full md:max-w-md">
                        <x-select name="student_id" onchange="this.form.submit()" :selected="$selectedStudentId" :options="$children->map(fn($c) => ['value' => $c->id, 'label' => $c->user->name ?? 'Anak'])->toArray()" />
                    </div>
                    <!-- Search bar if any -->
                    <div class="flex items-center gap-2 w-full md:w-auto mt-4 md:mt-0">
                        <x-text-input name="search" value="{{ request('search') }}" placeholder="Cari catatan..." class="w-full md:w-48 text-sm" />
                        <x-primary-button type="submit" class="py-2.5">Cari</x-primary-button>
                    </div>
                </div>
            </form>
        </div>

        @if(!$selectedStudent)
            <div class="bg-slate-50 border border-slate-200/75 rounded-2xl py-12 px-8 text-center shadow-sm max-w-3xl mx-auto w-full">
                <div class="h-16 w-16 bg-white border border-slate-200 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Anak Terdaftar</h3>
                <p class="text-sm text-slate-500 font-medium">Anda belum memiliki anak yang terdaftar pada sistem sekolah ini.</p>
            </div>
        @elseif($feedbacks->isEmpty())
            <div class="bg-slate-50 border border-slate-200/75 rounded-2xl py-12 px-8 text-center shadow-sm max-w-3xl mx-auto w-full">
                <div class="h-16 w-16 bg-white border border-slate-200 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">{{ request('search') ? 'Feedback Tidak Ditemukan' : 'Belum Ada Feedback' }}</h3>
                <p class="text-sm text-slate-500 font-medium">
                    {{ request('search') ? 'Tidak ada catatan yang cocok dengan pencarian Anda.' : 'Belum ada catatan atau masukan dari guru untuk anak Anda.' }}
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($feedbacks as $feedback)
                    <div class="bg-white border border-slate-200/75 rounded-2xl p-6 shadow-sm relative group hover:shadow-md transition flex flex-col h-full">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="font-bold text-slate-900 text-[15px] mb-1 line-clamp-1" title="{{ $feedback->title }}">
                                    {{ $feedback->title }}
                                </h3>
                                <p class="text-[12px] text-slate-500 font-medium">{{ $feedback->created_at->format('d M Y') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded border text-[10px] font-bold uppercase tracking-wider {{ $feedback->type === 'positive' ? 'bg-emerald-50 text-emerald-700 border-emerald-200/50' : ($feedback->type === 'negative' ? 'bg-red-50 text-red-700 border-red-200/50' : 'bg-slate-50 text-slate-600 border-slate-200/50') }}">
                                {{ $feedback->type_label }}
                            </span>
                        </div>
                        
                        <div class="pl-3 border-l-2 {{ $feedback->type === 'positive' ? 'border-emerald-300' : ($feedback->type === 'negative' ? 'border-red-300' : 'border-primary/30') }} mb-5 flex-1">
                            <p class="text-[13px] text-slate-700 italic line-clamp-4 leading-relaxed font-medium">"{{ $feedback->message }}"</p>
                        </div>
                        
                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 bg-white border border-slate-200/50 rounded-full flex items-center justify-center font-bold text-primary text-xs shrink-0 shadow-sm">
                                    {{ strtoupper(substr($feedback->teacher->user->name ?? 'G', 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-[12px] leading-tight">{{ $feedback->teacher->user->name ?? '-' }}</h4>
                                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">{{ $feedback->subject->name ?? 'Wali Kelas' }}</p>
                                </div>
                            </div>
                            <a href="{{ route('orangtua.feedbacks.show', $feedback->id) }}" class="text-[12px] font-bold text-primary hover:text-blue-700 transition">
                                Detail &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $feedbacks->links() }}</div>
        @endif
    </div>
</x-layouts.app>
