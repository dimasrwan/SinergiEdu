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

    if (empty($formattedOptions) && !empty((string)$slot)) {
        preg_match_all('/<option\s+[^>]*value=["\']([^"\']*)["\'][^>]*>(.*?)<\/option>/is', (string)$slot, $matches, PREG_SET_ORDER);
        if (!empty($matches)) {
            foreach ($matches as $m) {
                $val = trim($m[1]);
                $lbl = trim(strip_tags($m[2]));
                if ($val === '' && str_contains($m[0], 'disabled')) {
                    if (empty($placeholder) || $placeholder === '-- Pilih --') {
                        $placeholder = $lbl;
                    }
                    continue;
                }
                if ($selectedValue === null && str_contains($m[0], 'selected')) {
                    $selectedValue = $val;
                }
                $formattedOptions[] = ['value' => (string)$val, 'label' => html_entity_decode((string)$lbl)];
            }
        }
    }
@endphp

<div x-data="{
        open: false,
        selectedVal: '{{ $selectedValue }}',
        selectedLabel: '',
        options: @js($formattedOptions),
        placeholder: '{{ addslashes($placeholder) }}',
        init() {
            this.syncOptions();
            this.$watch('selectedVal', () => this.syncLabel());
        },
        syncOptions() {
            let selectEl = this.$refs.hiddenInput ? this.$refs.hiddenInput.previousElementSibling : null;
            // Get current options dynamically if updated in DOM
            let found = this.options.find(o => String(o.value) === String(this.selectedVal));
            this.selectedLabel = found ? found.label : (this.placeholder || '-- Pilih --');
        },
        syncLabel() {
            let found = this.options.find(o => String(o.value) === String(this.selectedVal));
            this.selectedLabel = found ? found.label : (this.placeholder || '-- Pilih --');
        },
        selectOption(val, label) {
            if ({{ $disabled ? 'true' : 'false' }}) return;
            this.selectedVal = val;
            this.selectedLabel = label;
            this.open = false;
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
            @click="open = !open" 
            @keydown.space.prevent="open = !open"
            @keydown.enter.prevent="open = !open"
            @keydown.arrow-down.prevent="open = true"
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

    <ul x-show="open" 
        x-transition:enter="transition ease-out duration-100" 
        x-transition:enter-start="transform opacity-0 scale-95" 
        x-transition:enter-end="transform opacity-100 scale-100" 
        x-transition:leave="transition ease-in duration-75" 
        x-transition:leave-start="transform opacity-100 scale-100" 
        x-transition:leave-end="transform opacity-0 scale-95" 
        class="absolute z-50 mt-1.5 w-full bg-white border border-slate-200 rounded-xl shadow-lg max-h-60 overflow-y-auto py-1 focus:outline-none" 
        role="listbox" 
        style="display: none;"
    >
        <template x-for="opt in options" :key="opt.value">
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
    </ul>
</div>
