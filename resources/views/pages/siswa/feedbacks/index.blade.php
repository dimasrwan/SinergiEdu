<x-layouts.app>
    <x-slot:title>Pesan & Umpan Balik</x-slot:title>

    <div class="w-full space-y-6">
        
        <!-- Header -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 mb-1">Pesan & Dukungan Belajar</h1>
                <p class="text-slate-500 text-sm max-w-xl">Lihat umpan balik evaluasi dari guru serta catatan dukungan belajar dari orang tua Anda di rumah.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-blue-50 border border-blue-100 px-4 py-2 rounded-xl text-center">
                    <span class="block text-[10px] font-bold text-blue-500 uppercase tracking-wider mb-0.5">Feedback Guru</span>
                    <span class="block text-sm font-bold text-blue-700">{{ $totalFeedbacks }} Pesan</span>
                </div>
                <div class="bg-emerald-50 border border-emerald-100 px-4 py-2 rounded-xl text-center">
                    <span class="block text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-0.5">Dukungan Ortu</span>
                    <span class="block text-sm font-bold text-emerald-700">{{ $totalSupports }} Catatan</span>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex border-b border-slate-200 bg-slate-100/60 p-1.5 rounded-2xl gap-1">
            <a href="{{ route('siswa.feedbacks.index', ['tab' => 'guru']) }}"
               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $tab === 'guru' ? 'bg-white text-primary shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                </svg>
                <span>Feedback dari Guru</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $tab === 'guru' ? 'bg-blue-100 text-blue-700' : 'bg-slate-200 text-slate-600' }}">
                    {{ $totalFeedbacks }}
                </span>
            </a>

            <a href="{{ route('siswa.feedbacks.index', ['tab' => 'orangtua']) }}"
               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $tab === 'orangtua' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
                <span>Dukungan Orang Tua</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $tab === 'orangtua' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                    {{ $totalSupports }}
                </span>
            </a>
        </div>

        <!-- TAB CONTENT: FEEDBACK GURU -->
        @if($tab === 'guru')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($feedbacks as $feedback)
                    @php
                        $isPositive = $feedback->type === 'positive';
                        $isNegative = $feedback->type === 'negative';
                        
                        if ($isPositive) {
                            $borderColor = 'border-emerald-100/60 bg-emerald-50/40 hover:border-emerald-200';
                            $badgeColor = 'bg-emerald-100 text-emerald-700';
                        } elseif ($isNegative) {
                            $borderColor = 'border-amber-100/60 bg-amber-50/40 hover:border-amber-200';
                            $badgeColor = 'bg-amber-100 text-amber-700';
                        } else {
                            $borderColor = 'border-slate-200/75 bg-slate-50/40 hover:border-slate-300';
                            $badgeColor = 'bg-slate-200 text-slate-700';
                        }
                    @endphp
                    <a href="{{ route('siswa.feedbacks.show', $feedback) }}" class="block border {{ $borderColor }} rounded-2xl p-5 flex flex-col h-full shadow-sm hover:shadow-md transition group">
                        <div class="flex items-start justify-between mb-4 gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="h-10 w-10 shrink-0 rounded-full bg-white border border-slate-200/50 text-primary flex items-center justify-center font-bold text-sm shadow-sm">
                                    {{ strtoupper(substr($feedback->teacher->user->name ?? 'G', 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-bold text-slate-900 text-sm truncate group-hover:text-primary transition-colors">
                                        {{ $feedback->teacher->user->name ?? '-' }}
                                    </h3>
                                    <p class="text-[11px] text-slate-500 truncate font-medium">{{ $feedback->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $badgeColor }}">
                                {{ $feedback->type_label ?? $feedback->type }}
                            </span>
                        </div>
                        
                        @if($feedback->subject)
                            <span class="inline-flex text-[10px] font-bold text-primary uppercase tracking-wider mb-2 w-fit">
                                {{ $feedback->subject->name }}
                            </span>
                        @endif
                        
                        <h4 class="text-[15px] font-bold text-slate-800 mb-1.5">{{ $feedback->title }}</h4>
                        <div class="flex-grow pl-3 border-l-2 border-slate-300/50 mt-1">
                            <p class="text-[13px] text-slate-700 line-clamp-3 leading-relaxed font-medium italic">
                                "{{ $feedback->message }}"
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full">
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl py-12 px-6 flex flex-col items-center text-center max-w-2xl mx-auto">
                            <div class="h-16 w-16 bg-blue-50 text-blue-400 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Feedback Guru</h3>
                            <p class="text-sm text-slate-500">Anda belum menerima pesan atau umpan balik evaluasi apapun dari bapak/ibu guru.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            
            @if($feedbacks->hasPages())
                <div class="mt-8">
                    {{ $feedbacks->appends(['tab' => 'guru'])->links() }}
                </div>
            @endif

        <!-- TAB CONTENT: DUKUNGAN ORANG TUA -->
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($parentSupports as $support)
                    <div class="bg-white border border-emerald-200/80 rounded-2xl p-5 flex flex-col h-full shadow-xs hover:shadow-md transition">
                        <div class="flex items-center justify-between gap-2 border-b border-emerald-100 pb-3 mb-3">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider">
                                    {{ $support->week_number }}
                                </span>
                                <span class="text-xs text-slate-500 font-medium">
                                    {{ $support->academicYear->year ?? '' }} {{ $support->semester->name ?? '' }}
                                </span>
                            </div>
                            <span class="text-[11px] text-slate-400 font-medium">
                                {{ $support->created_at->format('d M Y') }}
                            </span>
                        </div>

                        <div class="space-y-3 text-xs flex-1">
                            <div>
                                <p class="font-bold text-emerald-950 uppercase tracking-wider text-[10px] mb-1">Dukungan di Rumah:</p>
                                <p class="text-slate-700 font-medium leading-relaxed bg-emerald-50/40 p-3 rounded-xl border border-emerald-100 break-words">{{ $support->support_description }}</p>
                            </div>

                            @if($support->general_feedback)
                                <div>
                                    <p class="font-bold text-emerald-950 uppercase tracking-wider text-[10px] mb-1">Catatan Perkembangan:</p>
                                    <p class="text-slate-700 font-medium leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-200 italic break-words">{{ $support->general_feedback }}</p>
                                </div>
                            @endif

                            @if($support->action_plan)
                                <div>
                                    <p class="font-bold text-emerald-950 uppercase tracking-wider text-[10px] mb-1">Rencana Aksi Orang Tua:</p>
                                    <p class="text-slate-700 font-medium leading-relaxed bg-emerald-50/40 p-3 rounded-xl border border-emerald-100 break-words">{{ $support->action_plan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl py-12 px-6 flex flex-col items-center text-center max-w-2xl mx-auto">
                            <div class="h-16 w-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Catatan Dukungan Orang Tua</h3>
                            <p class="text-sm text-slate-500">Orang tua Anda belum mencatatkan rencana bimbingan dan dukungan belajar di rumah.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($parentSupports->hasPages())
                <div class="mt-8">
                    {{ $parentSupports->appends(['tab' => 'orangtua'])->links() }}
                </div>
            @endif
        @endif

    </div>
</x-layouts.app>
