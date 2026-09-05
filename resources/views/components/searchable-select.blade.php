@props([
    'name' => '',
    'id' => null,
    'options' => [],
    'selected' => null,
    'value' => null,
    'placeholder' => '-- Pilih --',
    'disabled' => false,
    'required' => false,
])

@php
    $selectedValue = $selected ?? $value ?? old($name);
    $elementId = $id ?? $name;
    $onchange = $attributes->get('onchange');

    $formattedOptions = [];
    if (is_array($options) || $options instanceof \Illuminate\Support\Collection) {
        foreach ($options as $key => $opt) {
            if (is_array($opt)) {
                $v = $opt['value'] ?? $opt['id'] ?? $key;
                $l = $opt['label'] ?? $opt['name'] ?? $v;
            } elseif (is_object($opt)) {
                $v = $opt->value ?? $opt->id ?? $key;
                $l = $opt->label ?? $opt->name ?? $v;
            } else {
                $v = is_numeric($key) && is_string($opt) ? $opt : $key;
                $l = $opt;
            }
            $formattedOptions[] = ['value' => (string)$v, 'label' => (string)$l];
        }
    }
@endphp

<div x-data="{
        open: false,
        search: '',
        selectedVal: '{{ $selectedValue }}',
        selectedLabel: '',
        options: @js($formattedOptions),
        placeholder: '{{ addslashes($placeholder) }}',
        get filteredOptions() {
            if (!this.search || this.search.trim() === '') return this.options;
            let query = this.search.toLowerCase().trim();
            return this.options.filter(o => o.label.toLowerCase().includes(query));
        },
        init() {
            let found = this.options.find(o => String(o.value) === String(this.selectedVal));
            this.selectedLabel = found ? found.label : (this.placeholder || '-- Pilih --');
        },
        selectOption(val, label) {
            if ({{ $disabled ? 'true' : 'false' }}) return;
            this.selectedVal = val;
            this.selectedLabel = label;
            this.open = false;
            this.search = '';
            this.$nextTick(() => {
                let input = this.$refs.hiddenInput;
                if (input) {
                    input.value = val;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    @if($onchange)
                        (function() { {!! $onchange !!} }).call(input);
                    @endif
                }
            });
        }
    }" 
    class="relative w-full" 
    @keydown.escape.prevent.stop="open = false" 
    @click.away="open = false"
>
    <input type="hidden" 
           name="{{ $name }}" 
           id="{{ $elementId }}" 
           x-ref="hiddenInput" 
           :value="selectedVal" 
           {{ $required ? 'required' : '' }} 
           {{ $disabled ? 'disabled' : '' }}>

    <button type="button" 
            @click="open = !open; if(open) $nextTick(() => $refs.searchInput?.focus())" 
            @keydown.space.prevent="open = !open; if(open) $nextTick(() => $refs.searchInput?.focus())"
            @keydown.enter.prevent="open = !open; if(open) $nextTick(() => $refs.searchInput?.focus())"
            @keydown.arrow-down.prevent="open = true; if(open) $nextTick(() => $refs.searchInput?.focus())"
            aria-haspopup="listbox" 
            :aria-expanded="open"
            {{ $disabled ? 'disabled' : '' }}
            {!! $attributes->except(['class', 'onchange', 'name', 'id', 'required', 'disabled'])->merge([
                'class' => 'w-full flex items-center justify-between bg-white border border-slate-200 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 rounded-xl px-4 py-2.5 text-[14px] font-medium text-slate-800 shadow-2xs transition-all disabled:bg-slate-100 disabled:opacity-75 disabled:cursor-not-allowed'
            ]) !!}
    >
        <span x-text="selectedLabel" :class="{'text-slate-400': !selectedVal && selectedVal !== 0}" class="truncate"></span>
        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200 shrink-0 ml-2" 
             :class="{'rotate-180': open}" 
             fill="none" 
             viewBox="0 0 24 24" 
             stroke-width="2" 
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100" 
         x-transition:enter-start="transform opacity-0 scale-95" 
         x-transition:enter-end="transform opacity-100 scale-100" 
         x-transition:leave="transition ease-in duration-75" 
         x-transition:leave-start="transform opacity-100 scale-100" 
         x-transition:leave-end="transform opacity-0 scale-95" 
         class="absolute z-50 mt-1.5 w-full bg-white border border-slate-200 rounded-xl shadow-lg max-h-64 overflow-hidden flex flex-col focus:outline-none" 
         style="display: none;"
    >
        <div class="p-2 border-b border-slate-100 bg-slate-50/50">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" 
                       x-model="search" 
                       x-ref="searchInput" 
                       placeholder="Cari pilihan..." 
                       class="w-full pl-8 pr-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-slate-800 placeholder:text-slate-400">
            </div>
        </div>

        <ul class="max-h-52 overflow-y-auto py-1" role="listbox">
            <template x-for="opt in filteredOptions" :key="opt.value">
                <li @click="selectOption(opt.value, opt.label)" 
                    class="cursor-pointer select-none px-4 py-2.5 text-[14px] transition-colors flex items-center justify-between"
                    :class="String(selectedVal) === String(opt.value) ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-50 font-medium'"
                    role="option"
                    :aria-selected="String(selectedVal) === String(opt.value)">
                    <span x-text="opt.label"></span>
                    <svg x-show="String(selectedVal) === String(opt.value)" 
                         class="h-4 w-4 text-blue-600 shrink-0 ml-2" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="2.5" 
                         stroke="currentColor" 
                         style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </li>
            </template>
            <li x-show="filteredOptions.length === 0" class="px-4 py-3 text-xs text-slate-400 text-center font-medium">
                Tidak ada pilihan yang sesuai
            </li>
        </ul>
    </div>
</div>