<div class="space-y-4" x-data="{ activeTab: 'pre_test' }">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Perkembangan per Pertemuan</h4>
        <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200 text-xs font-semibold">
            <button type="button" @click="activeTab = 'pre_test'" :class="activeTab === 'pre_test' ? 'bg-white text-blue-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg transition-all">Tes Awal</button>
            <button type="button" @click="activeTab = 'assignment'" :class="activeTab === 'assignment' ? 'bg-white text-indigo-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg transition-all">Tugas</button>
            <button type="button" @click="activeTab = 'post_test'" :class="activeTab === 'post_test' ? 'bg-white text-emerald-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg transition-all">Tes Akhir</button>
            <button type="button" @click="activeTab = 'character'" :class="activeTab === 'character' ? 'bg-white text-amber-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg transition-all">Karakter</button>
            <button type="button" @click="activeTab = 'memorization'" :class="activeTab === 'memorization' ? 'bg-white text-rose-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg transition-all">Hafalan</button>
        </div>
    </div>

    <div class="overflow-x-auto pb-2">
        <div class="flex items-end gap-6 min-w-[320px] pt-4">
            @foreach($assessments as $ast)
                @php
                    $meeting = $ast->learningMeeting;
                    $mAvgs = $meetingClassAverages[$ast->learning_meeting_id] ?? [];
                @endphp
                <div class="flex-1 min-w-[70px] space-y-2 group relative">
                    <div class="h-28 flex items-end gap-1.5 justify-center border-b border-slate-200 pb-1">
                        <!-- Student Bar -->
                        <div class="w-1/2 max-w-[20px] flex flex-col items-center justify-end h-full">
                            <span x-show="activeTab === 'pre_test'" class="text-[10px] font-bold text-blue-600 mb-1">{{ $ast->pre_test_score !== null ? $ast->pre_test_score : '-' }}</span>
                            <span x-show="activeTab === 'assignment'" class="text-[10px] font-bold text-indigo-600 mb-1" style="display: none;">{{ $ast->assignment_score !== null ? $ast->assignment_score : '-' }}</span>
                            <span x-show="activeTab === 'post_test'" class="text-[10px] font-bold text-emerald-600 mb-1" style="display: none;">{{ $ast->post_test_score !== null ? $ast->post_test_score : '-' }}</span>
                            <span x-show="activeTab === 'character'" class="text-[10px] font-bold text-amber-600 mb-1" style="display: none;">{{ $ast->character_score !== null ? $ast->character_score : '-' }}</span>
                            <span x-show="activeTab === 'memorization'" class="text-[10px] font-bold text-rose-600 mb-1" style="display: none;">{{ $ast->memorization_score !== null ? $ast->memorization_score : '-' }}</span>
                            
                            <div x-show="activeTab === 'pre_test'" class="w-full bg-blue-500 rounded-t-md transition-all duration-300" style="height: {{ $ast->pre_test_score !== null ? min(100, $ast->pre_test_score) : 0 }}%"></div>
                            <div x-show="activeTab === 'assignment'" class="w-full bg-indigo-500 rounded-t-md transition-all duration-300" style="height: {{ $ast->assignment_score !== null ? min(100, $ast->assignment_score) : 0 }}%; display: none;"></div>
                            <div x-show="activeTab === 'post_test'" class="w-full bg-emerald-500 rounded-t-md transition-all duration-300" style="height: {{ $ast->post_test_score !== null ? min(100, $ast->post_test_score) : 0 }}%; display: none;"></div>
                            <div x-show="activeTab === 'character'" class="w-full bg-amber-500 rounded-t-md transition-all duration-300" style="height: {{ $ast->character_score !== null ? min(100, $ast->character_score) : 0 }}%; display: none;"></div>
                            <div x-show="activeTab === 'memorization'" class="w-full bg-rose-500 rounded-t-md transition-all duration-300" style="height: {{ $ast->memorization_score !== null ? min(100, $ast->memorization_score) : 0 }}%; display: none;"></div>
                        </div>

                        <!-- Class Average Bar -->
                        <div class="w-1/2 max-w-[20px] flex flex-col items-center justify-end h-full">
                            <span x-show="activeTab === 'pre_test'" class="text-[9px] font-semibold text-slate-400 mb-1">{{ isset($mAvgs['pre_test']) ? $mAvgs['pre_test'] : '-' }}</span>
                            <span x-show="activeTab === 'assignment'" class="text-[9px] font-semibold text-slate-400 mb-1" style="display: none;">{{ isset($mAvgs['assignment']) ? $mAvgs['assignment'] : '-' }}</span>
                            <span x-show="activeTab === 'post_test'" class="text-[9px] font-semibold text-slate-400 mb-1" style="display: none;">{{ isset($mAvgs['post_test']) ? $mAvgs['post_test'] : '-' }}</span>
                            <span x-show="activeTab === 'character'" class="text-[9px] font-semibold text-slate-400 mb-1" style="display: none;">{{ isset($mAvgs['character']) ? $mAvgs['character'] : '-' }}</span>
                            <span x-show="activeTab === 'memorization'" class="text-[9px] font-semibold text-slate-400 mb-1" style="display: none;">{{ isset($mAvgs['memorization']) ? $mAvgs['memorization'] : '-' }}</span>

                            <div x-show="activeTab === 'pre_test'" class="w-full bg-slate-300 rounded-t-md transition-all duration-300" style="height: {{ isset($mAvgs['pre_test']) ? min(100, $mAvgs['pre_test']) : 0 }}%"></div>
                            <div x-show="activeTab === 'assignment'" class="w-full bg-slate-300 rounded-t-md transition-all duration-300" style="height: {{ isset($mAvgs['assignment']) ? min(100, $mAvgs['assignment']) : 0 }}%; display: none;"></div>
                            <div x-show="activeTab === 'post_test'" class="w-full bg-slate-300 rounded-t-md transition-all duration-300" style="height: {{ isset($mAvgs['post_test']) ? min(100, $mAvgs['post_test']) : 0 }}%; display: none;"></div>
                            <div x-show="activeTab === 'character'" class="w-full bg-slate-300 rounded-t-md transition-all duration-300" style="height: {{ isset($mAvgs['character']) ? min(100, $mAvgs['character']) : 0 }}%; display: none;"></div>
                            <div x-show="activeTab === 'memorization'" class="w-full bg-slate-300 rounded-t-md transition-all duration-300" style="height: {{ isset($mAvgs['memorization']) ? min(100, $mAvgs['memorization']) : 0 }}%; display: none;"></div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-xs font-bold text-slate-800">P{{ $meeting->meeting_number }}</div>
                        <div class="text-[10px] text-slate-400 truncate max-w-[80px] mx-auto" title="{{ $meeting->topic }}">{{ $meeting->topic }}</div>
                        <div class="text-[9px] text-slate-400 mt-0.5">{{ $meeting->meeting_date ? $meeting->meeting_date->format('d/m/Y') : '' }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 border-t border-slate-100">
        <div class="flex items-center gap-3">
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-blue-500 inline-block"></span> Nilai Siswa</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-slate-300 inline-block"></span> Rata-rata Kelas</span>
        </div>
        <span class="italic text-slate-400">P = Pertemuan ke-</span>
    </div>
</div>
