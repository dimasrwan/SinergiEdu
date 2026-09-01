<div class="space-y-2">
    <div class="flex justify-between text-xs font-semibold text-slate-500">
        <span>{{ $label }}</span>
    </div>
    <div class="flex items-end gap-2 h-16 pt-2">
        <div class="flex-1 flex flex-col justify-end h-full">
            <span class="text-[10px] text-center text-slate-400 font-bold mb-1">{{ $score ?? 0 }}</span>
            <div class="{{ $color }} rounded-t-md" style="height: {{ min(100, $score ?? 0) }}%"></div>
        </div>
        <div class="flex-1 flex flex-col justify-end h-full">
            <span class="text-[10px] text-center text-slate-400 font-bold mb-1">{{ $avg }}</span>
            <div class="bg-slate-300 rounded-t-md" style="height: {{ min(100, $avg) }}%"></div>
        </div>
    </div>
    <p class="text-[10px] text-center text-slate-500">Siswa vs Rata Kelas</p>
</div>
