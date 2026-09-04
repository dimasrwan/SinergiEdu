<x-layouts.app>
    <x-slot:title>Feedback Anak</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Feedback Anak" description="Lihat umpan balik yang diberikan guru untuk perkembangan anak Anda." />

        <x-card padding="md" class="border border-slate-200">
            <form action="{{ route('orangtua.feedbacks.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
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
                <!-- Search bar if any -->
                <div class="flex items-center gap-2">
                    <x-text-input name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full md:w-48" />
                    <x-primary-button type="submit">Cari</x-primary-button>
                </div>
            </form>
        </x-card>

        @if(!$selectedStudent)
            <div class="text-center bg-slate-50 border border-slate-200 py-10 rounded-xl">
                <p class="text-slate-500">Belum Ada Anak Terdaftar</p>
            </div>
        @elseif($feedbacks->isEmpty())
            <div class="text-center bg-slate-50 border border-slate-200 py-10 rounded-xl">
                <p class="text-slate-500">
                    {{ request('search') ? 'Tidak Ada Feedback yang Cocok' : 'Belum Ada Feedback dari Guru' }}
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($feedbacks as $feedback)
                    <x-card padding="none" class="flex flex-col hover:shadow-md transition-shadow duration-300">
                        <div class="p-6 flex flex-col h-full">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 line-clamp-1" title="{{ $feedback->title }}">
                                        {{ $feedback->title }}
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-1">{{ $feedback->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <x-badge :variant="$feedback->type === 'positive' ? 'success' : ($feedback->type === 'negative' ? 'danger' : 'slate')">
                                    {{ $feedback->type_label }}
                                </x-badge>
                            </div>

                            <div class="bg-slate-50 border {{ $feedback->type === 'positive' ? 'border-emerald-100' : ($feedback->type === 'negative' ? 'border-red-100' : 'border-slate-100') }} rounded-2xl p-4 text-slate-700 text-sm leading-relaxed whitespace-pre-wrap relative mb-4 flex-1">
                                <div class="absolute -top-2 left-6 w-4 h-4 {{ $feedback->type === 'positive' ? 'bg-emerald-50 border-t border-l border-emerald-100' : ($feedback->type === 'negative' ? 'bg-red-50 border-t border-l border-red-100' : 'bg-slate-50 border-t border-l border-slate-100') }} transform rotate-45 z-0"></div>
                                <div class="relative z-10 line-clamp-3">
                                    {{ $feedback->message }}
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-auto">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>
                                        <span class="font-semibold text-slate-700">{{ $feedback->teacher->user->name ?? '-' }}</span>
                                    </div>
                                    @if($feedback->subject)
                                        <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                            </svg>
                                            <span class="font-semibold text-slate-700">{{ $feedback->subject->name }}</span>
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('orangtua.feedbacks.show', $feedback->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 transition ease-in-out duration-150">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>

            <div class="mt-6">{{ $feedbacks->links() }}</div>
        @endif
    </div>
</x-layouts.app>
