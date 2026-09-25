@props([
    'name' => '',
    'id' => null,
    'value' => '',
    'placeholder' => 'DD/MM/YYYY HH:mm',
    'disabled' => false,
    'required' => false,
    'min' => null,
    'max' => null,
])

@php
    $elementId = $id ?? ($name ?: 'datetime_'.uniqid());
    $initialValue = old($name, $value ?? '');

    $initialDisplayValue = '';
    $initialCanonicalValue = '';

    if ($initialValue) {
        if ($initialValue instanceof \Carbon\Carbon || $initialValue instanceof \DateTimeInterface) {
            $initialDisplayValue = $initialValue->format('d/m/Y H:i');
            $initialCanonicalValue = $initialValue->format('Y-m-d\TH:i');
            $initialValue = $initialCanonicalValue;
        } else {
            try {
                $parsed = \Carbon\Carbon::parse($initialValue);
                $initialDisplayValue = $parsed->format('d/m/Y H:i');
                $initialCanonicalValue = $parsed->format('Y-m-d\TH:i');
            } catch (\Throwable $e) {
                $initialDisplayValue = (string) $initialValue;
                $initialCanonicalValue = (string) $initialValue;
            }
        }
    }
@endphp

<div x-data="{
        open: false,
        viewMode: 'calendar', // 'calendar', 'month', 'year'
        selectedDate: '', // YYYY-MM-DD
        selectedHour: '23',
        selectedMinute: '59',
        displayInput: '{{ addslashes($initialDisplayValue) }}',
        currentYear: new Date().getFullYear(),
        currentMonth: new Date().getMonth(),
        decadeStartYear: Math.floor(new Date().getFullYear() / 12) * 12,
        monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
        blankDays: [],
        monthDays: [],

        hoursList: Array.from({ length: 24 }, (_, i) => String(i).padStart(2, '0')),
        minutesList: ['00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55', '59'],

        init() {
            let rawInit = '{{ addslashes($initialValue) }}';
            if (rawInit) {
                this.parseAndSetInitial(rawInit);
            } else {
                this.displayInput = '';
                this.selectedDate = '';
                this.selectedHour = '23';
                this.selectedMinute = '59';
            }
            this.decadeStartYear = Math.floor(this.currentYear / 12) * 12;
            this.generateCalendar();
        },

        parseAndSetInitial(str) {
            let clean = str.replace('T', ' ').trim();
            // Try YYYY-MM-DD HH:mm
            let parts = clean.split(' ');
            if (parts.length >= 1 && parts[0].includes('-')) {
                let dParts = parts[0].split('-');
                if (dParts.length === 3) {
                    let y = parseInt(dParts[0], 10);
                    let m = parseInt(dParts[1], 10) - 1;
                    let d = parseInt(dParts[2], 10);
                    if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
                        this.currentYear = y;
                        this.currentMonth = m;
                        this.selectedDate = `${y}-${String(m + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                    }
                }
            }
            if (parts.length >= 2 && parts[1].includes(':')) {
                let tParts = parts[1].split(':');
                if (tParts.length >= 2) {
                    this.selectedHour = String(parseInt(tParts[0], 10)).padStart(2, '0');
                    this.selectedMinute = String(parseInt(tParts[1], 10)).padStart(2, '0');
                }
            }

            if (this.selectedDate) {
                let dParts = this.selectedDate.split('-');
                this.displayInput = `${dParts[2].padStart(2, '0')}/${dParts[1].padStart(2, '0')}/${dParts[0]} ${this.selectedHour}:${this.selectedMinute}`;
            } else {
                this.displayInput = str;
            }
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
            this.currentYear = yr;
            this.generateCalendar();
            this.viewMode = 'calendar';
        },

        prevDecade() {
            this.decadeStartYear -= 12;
        },

        nextDecade() {
            this.decadeStartYear += 12;
        },

        get visibleYears() {
            return Array.from({ length: 12 }, (_, i) => this.decadeStartYear + i);
        },

        selectDate(day) {
            if ({{ $disabled ? 'true' : 'false' }}) return;
            let monthStr = String(this.currentMonth + 1).padStart(2, '0');
            let dayStr = String(day).padStart(2, '0');
            this.selectedDate = `${this.currentYear}-${monthStr}-${dayStr}`;
            this.syncDisplayAndHidden();
        },

        selectHour(hr) {
            this.selectedHour = hr;
            if (!this.selectedDate) {
                let monthStr = String(this.currentMonth + 1).padStart(2, '0');
                let dayStr = String(new Date().getDate()).padStart(2, '0');
                this.selectedDate = `${this.currentYear}-${monthStr}-${dayStr}`;
            }
            this.syncDisplayAndHidden();
        },

        selectMinute(min) {
            this.selectedMinute = min;
            if (!this.selectedDate) {
                let monthStr = String(this.currentMonth + 1).padStart(2, '0');
                let dayStr = String(new Date().getDate()).padStart(2, '0');
                this.selectedDate = `${this.currentYear}-${monthStr}-${dayStr}`;
            }
            this.syncDisplayAndHidden();
        },

        syncDisplayAndHidden() {
            if (this.selectedDate) {
                let dParts = this.selectedDate.split('-');
                let dayStr = dParts[2].padStart(2, '0');
                let monthStr = dParts[1].padStart(2, '0');
                let yearStr = dParts[0];
                this.displayInput = `${dayStr}/${monthStr}/${yearStr} ${this.selectedHour}:${this.selectedMinute}`;
            } else {
                this.displayInput = '';
            }
            this.updateInput();
        },

        clearDateTime() {
            if ({{ $disabled ? 'true' : 'false' }}) return;
            this.selectedDate = '';
            this.selectedHour = '23';
            this.selectedMinute = '59';
            this.displayInput = '';
            this.open = false;
            this.viewMode = 'calendar';
            this.updateInput();
        },

        applyDateTime() {
            if (!this.selectedDate) {
                let monthStr = String(this.currentMonth + 1).padStart(2, '0');
                let dayStr = String(new Date().getDate()).padStart(2, '0');
                this.selectedDate = `${this.currentYear}-${monthStr}-${dayStr}`;
            }
            this.syncDisplayAndHidden();
            this.open = false;
            this.viewMode = 'calendar';
        },

        updateInput() {
            this.$nextTick(() => {
                let hiddenValue = '';
                if (this.selectedDate) {
                    hiddenValue = `${this.selectedDate}T${this.selectedHour}:${this.selectedMinute}`;
                } else if (this.displayInput) {
                    hiddenValue = this.displayInput;
                }
                let input = this.$refs.hiddenInput;
                if (input) {
                    input.value = hiddenValue;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        },

        formatMask(val) {
            let digits = val.replace(/\D/g, '').slice(0, 12);
            if (!digits) return '';
            
            let result = '';
            // DD
            if (digits.length <= 2) return digits;
            result += `${digits.slice(0, 2)}/`;
            
            // MM
            if (digits.length <= 4) return result + digits.slice(2);
            result += `${digits.slice(2, 4)}/`;

            // YYYY
            if (digits.length <= 8) return result + digits.slice(4);
            result += `${digits.slice(4, 8)} `;

            // HH
            if (digits.length <= 10) return result + digits.slice(8);
            result += `${digits.slice(8, 10)}:`;

            // mm
            return result + digits.slice(10, 12);
        },

        onTextInput(val) {
            let formatted = this.formatMask(val);
            this.displayInput = formatted;

            if (!formatted) {
                this.selectedDate = '';
                this.updateInput();
                return;
            }

            // Match DD/MM/YYYY HH:mm
            let matchFull = formatted.match(/^(\d{2})\/(\d{2})\/(\d{4})\s+(\d{2}):(\d{2})$/);
            if (matchFull) {
                let day = parseInt(matchFull[1], 10);
                let month = parseInt(matchFull[2], 10);
                let year = parseInt(matchFull[3], 10);
                let hr = parseInt(matchFull[4], 10);
                let min = parseInt(matchFull[5], 10);

                if (month >= 1 && month <= 12 && day >= 1 && day <= 31 && hr >= 0 && hr <= 23 && min >= 0 && min <= 59 && year >= 1900 && year <= 2100) {
                    let d = new Date(year, month - 1, day);
                    if (d.getFullYear() === year && d.getMonth() === month - 1 && d.getDate() === day) {
                        let monthStr = String(month).padStart(2, '0');
                        let dayStr = String(day).padStart(2, '0');
                        this.selectedDate = `${year}-${monthStr}-${dayStr}`;
                        this.selectedHour = String(hr).padStart(2, '0');
                        this.selectedMinute = String(min).padStart(2, '0');
                        this.currentYear = year;
                        this.currentMonth = month - 1;
                        this.generateCalendar();
                        this.updateInput();
                        return;
                    }
                }
            }

            // Match DD/MM/YYYY date only
            let matchDate = formatted.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
            if (matchDate) {
                let day = parseInt(matchDate[1], 10);
                let month = parseInt(matchDate[2], 10);
                let year = parseInt(matchDate[3], 10);

                if (month >= 1 && month <= 12 && day >= 1 && day <= 31 && year >= 1900 && year <= 2100) {
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
        },

        get formattedHiddenValue() {
            if (this.selectedDate) {
                return `${this.selectedDate}T${this.selectedHour}:${this.selectedMinute}`;
            }
            return this.displayInput;
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
           value="{{ $initialCanonicalValue }}"
           :value="formattedHiddenValue"
           {{ $required ? 'required' : '' }}
           {{ $disabled ? 'disabled' : '' }}>

    <div class="relative w-full">
        <input type="text"
               inputmode="numeric"
               maxlength="16"
               value="{{ $initialDisplayValue }}"
               x-model="displayInput"
               @input="onTextInput($event.target.value)"
               @focus="open = true; viewMode = 'calendar'"
               placeholder="{{ $placeholder }}"
               aria-label="{{ $placeholder }}"
               {{ $disabled ? 'disabled' : '' }}
               {!! $attributes->except(['class', 'name', 'id', 'required', 'disabled', 'value', 'type'])->merge([
                   'class' => 'w-full h-10 bg-white border border-slate-300 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 rounded-xl pl-3.5 pr-10 text-sm font-medium text-slate-800 shadow-2xs transition-all disabled:bg-slate-100 disabled:opacity-75 disabled:cursor-not-allowed font-mono tracking-tight'
               ]) !!}
        >
        <button type="button"
                @click="open = !open; if(open) viewMode = 'calendar'"
                tabindex="-1"
                aria-label="Buka tenggat waktu"
                {{ $disabled ? 'disabled' : '' }}
                class="absolute right-0 top-0 bottom-0 px-3 flex items-center justify-center text-slate-400 hover:text-slate-600 focus:outline-none disabled:cursor-not-allowed"
        >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>
    </div>

    {{-- Datetime Picker Popover --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-50 mt-1.5 w-80 sm:w-84 bg-white border border-slate-200/90 rounded-2xl shadow-xl p-4 focus:outline-none"
         style="display: none;"
    >
        {{-- Header Status --}}
        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-2.5 mb-3 flex items-center justify-between text-xs font-semibold text-slate-700">
            <span class="text-slate-400 uppercase tracking-wider text-[10px]">Deadline:</span>
            <span class="font-bold text-primary font-mono" x-text="displayInput || 'dd/mm/yyyy --:--'"></span>
        </div>

        {{-- VIEW MODE 1: CALENDAR VIEW WITH TIME SECTION --}}
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
                <div class="grid grid-cols-7 gap-1 text-center py-1 border-b border-slate-100 pb-3">
                    <template x-for="blank in blankDays" :key="'b-'+blank">
                        <div class="h-8 w-8"></div>
                    </template>

                    <template x-for="day in monthDays" :key="'d-'+day">
                        <button type="button"
                                @click="selectDate(day)"
                                class="h-8 w-8 mx-auto flex items-center justify-center text-xs font-semibold rounded-lg transition-colors select-none"
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

                {{-- TIME SECTION --}}
                <div class="pt-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Waktu Deadline
                        </span>
                        <span class="text-xs font-mono font-bold text-primary bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-md" x-text="`${selectedHour}:${selectedMinute}`"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block mb-1 uppercase">Jam</span>
                            <select x-model="selectedHour"
                                    @change="selectHour($event.target.value)"
                                    aria-label="Pilih Jam"
                                    class="w-full py-1.5 px-2 text-xs font-semibold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer font-mono"
                            >
                                <template x-for="hr in hoursList" :key="hr">
                                    <option :value="hr" x-text="hr" :selected="selectedHour === hr"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block mb-1 uppercase">Menit</span>
                            <select x-model="selectedMinute"
                                    @change="selectMinute($event.target.value)"
                                    aria-label="Pilih Menit"
                                    class="w-full py-1.5 px-2 text-xs font-semibold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer font-mono"
                            >
                                <template x-for="mn in minutesList" :key="mn">
                                    <option :value="mn" x-text="mn" :selected="selectedMinute === mn"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="flex items-center justify-between pt-3 mt-3 border-t border-slate-100">
                    <button type="button" @click="clearDateTime()" class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors">
                        Bersihkan
                    </button>
                    <button type="button" @click="applyDateTime()" class="px-4 py-1.5 text-xs font-bold text-white bg-primary hover:bg-blue-900 rounded-lg transition-colors shadow-2xs">
                        Terapkan
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
