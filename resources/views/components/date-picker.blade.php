@props([
    'name' => '',
    'id' => null,
    'value' => '',
    'placeholder' => 'Pilih tanggal...',
    'disabled' => false,
    'required' => false,
    'min' => null,
    'max' => null,
])

@php
    $elementId = $id ?? ($name ?: 'date_'.uniqid());
    $initialValue = old($name, $value ?? '');
    if ($initialValue instanceof \Carbon\Carbon || $initialValue instanceof \DateTimeInterface) {
        $initialValue = $initialValue->format('Y-m-d');
    }
@endphp

<div x-data="{
        open: false,
        selectedDate: '{{ $initialValue }}',
        currentYear: new Date().getFullYear(),
        currentMonth: new Date().getMonth(),
        monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
        blankDays: [],
        monthDays: [],

        init() {
            if (this.selectedDate) {
                let d = new Date(this.selectedDate + 'T00:00:00');
                if (!isNaN(d.getTime())) {
                    this.currentYear = d.getFullYear();
                    this.currentMonth = d.getMonth();
                }
            }
            this.generateCalendar();
        },

        generateCalendar() {
            let firstDayOfMonth = new Date(this.currentYear, this.currentMonth, 1).getDay();
            let daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();

            this.blankDays = Array.from({ length: firstDayOfMonth }, (_, i) => i);
            this.monthDays = Array.from({ length: daysInMonth }, (_, i) => i + 1);
        },

        prevMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
            this.generateCalendar();
        },

        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
            this.generateCalendar();
        },

        selectDate(day) {
            if ({{ $disabled ? 'true' : 'false' }}) return;
            let monthStr = String(this.currentMonth + 1).padStart(2, '0');
            let dayStr = String(day).padStart(2, '0');
            this.selectedDate = `${this.currentYear}-${monthStr}-${dayStr}`;
            this.open = false;
            this.updateInput();
        },

        clearDate() {
            if ({{ $disabled ? 'true' : 'false' }}) return;
            this.selectedDate = '';
            this.open = false;
            this.updateInput();
        },

        selectToday() {
            if ({{ $disabled ? 'true' : 'false' }}) return;
            let now = new Date();
            this.currentYear = now.getFullYear();
            this.currentMonth = now.getMonth();
            let monthStr = String(this.currentMonth + 1).padStart(2, '0');
            let dayStr = String(now.getDate()).padStart(2, '0');
            this.selectedDate = `${this.currentYear}-${monthStr}-${dayStr}`;
            this.generateCalendar();
            this.open = false;
            this.updateInput();
        },

        updateInput() {
            this.$nextTick(() => {
                let input = this.$refs.hiddenInput;
                if (input) {
                    input.value = this.selectedDate;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        },

        formatDisplay(dateStr) {
            if (!dateStr) return '';
            let d = new Date(dateStr + 'T00:00:00');
            if (isNaN(d.getTime())) return dateStr;
            let day = d.getDate();
            let month = this.monthNames[d.getMonth()];
            let year = d.getFullYear();
            return `${day} ${month} ${year}`;
        },

        isToday(day) {
            let today = new Date();
            return today.getFullYear() === this.currentYear &&
                   today.getMonth() === this.currentMonth &&
                   today.getDate() === day;
        },

        isSelected(day) {
            if (!this.selectedDate) return false;
            let monthStr = String(this.currentMonth + 1).padStart(2, '0');
            let dayStr = String(day).padStart(2, '0');
            return this.selectedDate === `${this.currentYear}-${monthStr}-${dayStr}`;
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
           :value="selectedDate"
           {{ $required ? 'required' : '' }}
           {{ $disabled ? 'disabled' : '' }}>

    <div class="relative w-full">
        <button type="button"
                @click="open = !open"
                {{ $disabled ? 'disabled' : '' }}
                {!! $attributes->except(['class', 'name', 'id', 'required', 'disabled', 'value', 'type'])->merge([
                    'class' => 'w-full h-10 flex items-center justify-between bg-white border border-slate-300 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 rounded-xl px-3.5 text-sm font-medium text-slate-800 shadow-2xs transition-all disabled:bg-slate-100 disabled:opacity-75 disabled:cursor-not-allowed'
                ]) !!}
        >
            <span x-text="formatDisplay(selectedDate) || '{{ addslashes($placeholder) }}'"
                  :class="{'text-slate-400': !selectedDate}"
                  class="truncate pr-2"></span>
            <svg class="w-5 h-5 text-slate-400 shrink-0 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
            </svg>
        </button>
    </div>

    {{-- Calendar Popover --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-50 mt-1.5 w-72 sm:w-80 bg-white border border-slate-200/90 rounded-2xl shadow-xl p-4 focus:outline-none"
         style="display: none;"
    >
        {{-- Calendar Header --}}
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <span class="text-sm font-bold text-slate-900 tracking-tight" x-text="monthNames[currentMonth] + ' ' + currentYear"></span>
            <div class="flex items-center gap-1">
                <button type="button" @click="prevMonth()" class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition-colors" aria-label="Bulan sebelumnya">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <button type="button" @click="nextMonth()" class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition-colors" aria-label="Bulan selanjutnya">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Weekday Headers --}}
        <div class="grid grid-cols-7 gap-1 pt-3 pb-1 text-center">
            <template x-for="day in daysOfWeek" :key="day">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider" x-text="day"></span>
            </template>
        </div>

        {{-- Date Grid --}}
        <div class="grid grid-cols-7 gap-1 text-center py-1">
            {{-- Blank Days --}}
            <template x-for="blank in blankDays" :key="'b-'+blank">
                <div class="h-9 w-9 sm:h-8 sm:w-8"></div>
            </template>

            {{-- Month Days --}}
            <template x-for="day in monthDays" :key="'d-'+day">
                <button type="button"
                        @click="selectDate(day)"
                        class="h-9 w-9 sm:h-8 sm:w-8 mx-auto flex items-center justify-center text-xs font-semibold rounded-lg transition-colors select-none"
                        :class="{
                            'bg-primary text-white font-bold shadow-xs': isSelected(day),
                            'text-primary font-bold ring-2 ring-primary/30 bg-blue-50/50': isToday(day) && !isSelected(day),
                            'text-slate-700 hover:bg-slate-100': !isSelected(day) && !isToday(day)
                        }"
                >
                    <span x-text="day"></span>
                </button>
            </template>
        </div>

        {{-- Footer Actions --}}
        <div class="flex items-center justify-between pt-3 mt-1 border-t border-slate-100">
            <button type="button" @click="clearDate()" class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors">
                Clear
            </button>
            <button type="button" @click="selectToday()" class="px-3 py-1.5 text-xs font-bold text-primary hover:bg-blue-50 rounded-lg transition-colors">
                Hari ini
            </button>
        </div>
    </div>
</div>
