<div class="space-y-2 min-w-0">
    <div class="flex justify-between text-xs font-semibold text-slate-500">
        <span class="truncate">{{ $label }}</span>
    </div>
    <div class="flex items-end gap-2 h-16 pt-2">
        <div class="flex-1 flex flex-col justify-end h-full min-w-0">
            <span class="text-[10px] text-center text-slate-500 font-bold mb-1 truncate">{{ $score !== null ? $score : '-' }}</span>
            <div class="{{ $color }} rounded-t-md transition-all duration-300" style="height: {{ $score !== null ? min(100, (float)$score) : 0 }}%"></div>
        </div>
        <div class="flex-1 flex flex-col justify-end h-full min-w-0">
            <span class="text-[10px] text-center text-slate-400 font-bold mb-1 truncate">{{ $avg !== null && $avg > 0 ? $avg : '-' }}</span>
            <div class="bg-slate-300 rounded-t-md transition-all duration-300" style="height: {{ $avg !== null ? min(100, (float)$avg) : 0 }}%"></div>
        </div>
    </div>
    <p class="text-[10px] text-center text-slate-500 truncate">Siswa vs Rata Kelas</p>
</div>

