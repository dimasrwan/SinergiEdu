@props(['options', 'name', 'selected' => null])

<div x-data="{ 
    open: false, 
    search: '', 
    selected: '{{ $selected }}',
    options: @js($options), 
    get filtered() { 
        return this.options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase())) 
    } 
}" class="relative">
    <div @click="open = !open" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm cursor-pointer flex justify-between items-center">
        <span x-text="options.find(o => o.value == selected)?.label || '-- Pilih --'"></span>
        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
    </div>
    <div x-show="open" @click.away="open = false" class="absolute w-full border border-slate-200 mt-1 bg-white z-50 rounded-xl shadow-lg max-h-60 overflow-y-auto" style="display: none;">
        <input x-model="search" placeholder="Cari..." class="w-full p-3 border-b border-slate-100 text-sm focus:outline-none">
        <template x-for="option in filtered" :key="option.value">
            <div @click="selected = option.value; open = false; $refs.input.value = option.value; $refs.input.dispatchEvent(new Event('change'))" 
                 x-text="option.label" 
                 class="p-3 text-sm cursor-pointer hover:bg-slate-50"></div>
        </template>
    </div>
    <input type="hidden" name="{{ $name }}" x-ref="input" value="{{ $selected }}">
</div>