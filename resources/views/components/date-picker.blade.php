@props([
    'name' => '',
    'id' => null,
    'value' => '',
    'placeholder' => 'DD/MM/YYYY',
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
        viewMode: 'calendar', // 'calendar', 'month', 'year'
        selectedDate: '{{ $initialValue }}',
        displayInput: '',
        currentYear: new Date().getFullYear(),
        currentMonth: new Date().getMonth(),
        decadeStartYear: Math.floor(new Date().getFullYear() / 12) * 12,
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
                    this.displayInput = this.formatDisplayDDMMYYYY(this.selectedDate);
                } else {
                    this.displayInput = this.selectedDate;
                }
            } else {
                this.displayInput = '';
            }
            this.decadeStartYear = Math.floor(this.currentYear / 12) * 12;
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

        openMonthPicker() {
            this.viewMode = 'month';
        },

        openYearPicker() {
            this.decadeStartYear = Math.floor(this.currentYear / 12) * 12;
            this.viewMode = 'year';
        },

        selectMonth(index) {
            this.currentMonth = index;
            this.generateCalendar();
            this.viewMode = 'calendar';
        },

        selectYear(yr) {
            let maxAllowedYear = new Date().getFullYear() + 10;
            if (yr > maxAllowedYear) return;
            this.currentYear = yr;
            this.generateCalendar();
            this.viewMode = 'calendar';
        },

        prevDecade() {
            this.decadeStartYear -= 12;
        },

        nextDecade() {
            let maxAllowedYear = new Date().getFullYear() + 10;
            if (this.decadeStartYear + 12 <= maxAllowedYear) {
                this.decadeStartYear += 12;
            }
        },

        get visibleYears() {
            return Array.from({ length: 12 }, (_, i) => this.decadeStartYear + i);
        },

        selectDate(day) {
            if ({{ $disabled ? 'true' : 'false' }}) return;
            let monthStr = String(this.currentMonth + 1).padStart(2, '0');
            let dayStr = String(day).padStart(2, '0');
            this.selectedDate = `${this.currentYear}-${monthStr}-${dayStr}`;
            this.displayInput = `${dayStr}/${monthStr}/${this.currentYear}`;
            this.open = false;
            this.viewMode = 'calendar';
            this.updateInput();
        },

        clearDate() {
            if ({{ $disabled ? 'true' : 'false' }}) return;
            this.selectedDate = '';
            this.displayInput = '';
            this.open = false;
            this.viewMode = 'calendar';
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
            this.displayInput = `${dayStr}/${monthStr}/${this.currentYear}`;
            this.generateCalendar();
            this.open = false;
            this.viewMode = 'calendar';
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

        formatDisplayDDMMYYYY(dateStr) {
            if (!dateStr) return '';
            let parts = dateStr.split('-');
            if (parts.length === 3) {
                return `${parts[2].padStart(2, '0')}/${parts[1].padStart(2, '0')}/${parts[0]}`;
            }
            return dateStr;
        },

        formatMask(val) {
            let digits = val.replace(/\D/g, '').slice(0, 8);
            if (!digits) return '';
            if (digits.length <= 2) {
                return digits;
            }
            if (digits.length <= 4) {
                return `${digits.slice(0, 2)}/${digits.slice(2)}`;
            }
            return `${digits.slice(0, 2)}/${digits.slice(2, 4)}/${digits.slice(4)}`;
        },

        onTextInput(val) {
            let formatted = this.formatMask(val);
            this.displayInput = formatted;

            if (!formatted) {
                this.selectedDate = '';
                this.updateInput();
                return;
            }

            // Match DD/MM/YYYY
            let ddmmyyyyMatch = formatted.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
            if (ddmmyyyyMatch) {
                let day = parseInt(ddmmyyyyMatch[1], 10);
                let month = parseInt(ddmmyyyyMatch[2], 10);
                let year = parseInt(ddmmyyyyMatch[3], 10);

                if (month >= 1 && month <= 12 && day >= 1 && day <= 31 && year >= 1900 && year <= 2100) {
                    let d = new Date(year, month - 1, day);
                    if (d.getFullYear() === year && d.getMonth() === month - 1 && d.getDate() === day) {
                        let monthStr = String(month).padStart(2, '0');
                        let dayStr = String(day).padStart(2, '0');
                        this.selectedDate = `${year}-${monthStr}-${dayStr}`;
                        this.currentYear = year;
                        this.currentMonth = month - 1;
                        this.generateCalendar();
                        this.updateInput();
                        return;
                    }
                }
            }

            // If text is not complete or not valid YYYY-MM-DD yet, pass formatted so Laravel validation catches invalid format
            this.selectedDate = formatted;
            this.updateInput();
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
    @keydown.escape.prevent.stop="open = false; viewMode = 'calendar'"
    @click.away="open = false; viewMode = 'calendar'"
>
    <input type="hidden"
           name="{{ $name }}"
           id="{{ $elementId }}"
           x-ref="hiddenInput"
           :value="selectedDate"
           {{ $required ? 'required' : '' }}
           {{ $disabled ? 'disabled' : '' }}>

    <div class="relative w-full">
        <input type="text"
               inputmode="numeric"
               maxlength="10"
               x-model="displayInput"
               @input="onTextInput($event.target.value)"
               @focus="open = true; viewMode = 'calendar'"
               placeholder="{{ $placeholder }}"
               aria-label="{{ $placeholder }}"
               {{ $disabled ? 'disabled' : '' }}
               {!! $attributes->except(['class', 'name', 'id', 'required', 'disabled', 'value', 'type'])->merge([
                   'class' => 'w-full h-10 bg-white border border-slate-300 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 rounded-xl pl-3.5 pr-10 text-sm font-medium text-slate-800 shadow-2xs transition-all disabled:bg-slate-100 disabled:opacity-75 disabled:cursor-not-allowed'
               ]) !!}
        >
        <button type="button"
                @click="open = !open; if(open) viewMode = 'calendar'"
                tabindex="-1"
                aria-label="Buka kalender"
                {{ $disabled ? 'disabled' : '' }}
                class="absolute right-0 top-0 bottom-0 px-3 flex items-center justify-center text-slate-400 hover:text-slate-600 focus:outline-none disabled:cursor-not-allowed"
        >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
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
        {{-- VIEW MODE 1: CALENDAR VIEW --}}
        <template x-if="viewMode === 'calendar'">
            <div>
                {{-- Calendar Header --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 gap-2">
                    <div class="flex items-center gap-1 min-w-0 flex-1">
                        <button type="button"
                                @click="openMonthPicker()"
                                aria-label="Pilih Bulan"
                                class="px-2.5 py-1 text-xs font-bold text-slate-800 bg-slate-50 hover:bg-blue-50 hover:text-primary border border-slate-200 rounded-lg transition-colors inline-flex items-center gap-1"
                        >
                            <span x-text="monthNames[currentMonth]"></span>
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        </button>

                        <button type="button"
                                @click="openYearPicker()"
                                aria-label="Pilih Tahun"
                                class="px-2.5 py-1 text-xs font-bold text-slate-800 bg-slate-50 hover:bg-blue-50 hover:text-primary border border-slate-200 rounded-lg transition-colors inline-flex items-center gap-1"
                        >
                            <span x-text="currentYear"></span>
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        </button>
                    </div>

                    <div class="flex items-center gap-1 shrink-0">
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
                        Bersihkan
                    </button>
                    <button type="button" @click="selectToday()" class="px-3 py-1.5 text-xs font-bold text-primary hover:bg-blue-50 rounded-lg transition-colors">
                        Hari ini
                    </button>
                </div>
            </div>
        </template>

        {{-- VIEW MODE 2: CUSTOM MONTH PICKER GRID (3 COLUMNS) --}}
        <template x-if="viewMode === 'month'">
            <div>
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Pilih Bulan</h3>
                    <button type="button" @click="viewMode = 'calendar'" class="text-xs font-semibold text-primary hover:text-blue-700">
                        Kembali
                    </button>
                </div>

                <div class="grid grid-cols-3 gap-2 py-1">
                    <template x-for="(mName, idx) in monthNames" :key="idx">
                        <button type="button"
                                @click="selectMonth(idx)"
                                class="h-10 px-2 flex items-center justify-center text-xs font-semibold rounded-xl transition-all select-none border"
                                :class="{
                                    'bg-primary text-white font-bold border-primary shadow-xs': currentMonth === idx,
                                    'bg-white text-slate-700 border-slate-200 hover:bg-blue-50 hover:border-blue-200 hover:text-primary': currentMonth !== idx
                                }"
                        >
                            <span x-text="mName"></span>
                        </button>
                    </template>
                </div>
            </div>
        </template>

        {{-- VIEW MODE 3: CUSTOM YEAR PICKER GRID (4 COLUMNS) WITH DECADE PAGINATION --}}
        <template x-if="viewMode === 'year'">
            <div>
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                    <div class="flex items-center gap-1.5">
                        <button type="button" @click="prevDecade()" class="p-1 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition-colors" aria-label="Dekade sebelumnya">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                        </button>
                        <span class="text-xs font-bold text-slate-800 tracking-tight" x-text="`${decadeStartYear} – ${decadeStartYear + 11}`"></span>
                        <button type="button" @click="nextDecade()" class="p-1 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition-colors" aria-label="Dekade selanjutnya">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                        </button>
                    </div>
                    <button type="button" @click="viewMode = 'calendar'" class="text-xs font-semibold text-primary hover:text-blue-700">
                        Kembali
                    </button>
                </div>

                <div class="grid grid-cols-4 gap-2 py-1">
                    <template x-for="yr in visibleYears" :key="yr">
                        <button type="button"
                                @click="selectYear(yr)"
                                class="h-10 px-1 flex items-center justify-center text-xs font-semibold rounded-xl transition-all select-none border"
                                :class="{
                                    'bg-primary text-white font-bold border-primary shadow-xs': currentYear === yr,
                                    'bg-white text-slate-700 border-slate-200 hover:bg-blue-50 hover:border-blue-200 hover:text-primary': currentYear !== yr
                                }"
                        >
                            <span x-text="yr"></span>
                        </button>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>


