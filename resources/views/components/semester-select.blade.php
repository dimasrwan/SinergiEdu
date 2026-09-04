@props([
    'selected' => null,
    'required' => false,
    'emptyLabel' => null,
    'disabledEmpty' => false,
    'class' => null,
])

@php
    $semesters = \App\Models\Semester::with('academicYear')->orderBy('id')->get();
    $hasSelection = $selected !== null && $selected !== '';
@endphp

<select {{ $attributes->merge(['class' => $class ?? 'w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-accent focus:ring-accent/20 focus:bg-white transition duration-200']) }} {{ $required ? 'required' : '' }}>
    @if($emptyLabel !== null)
        <option value="" @disabled($disabledEmpty) @selected(!$hasSelection)>{{ $emptyLabel }}</option>
    @endif
    @forelse($semesters as $semester)
        <option value="{{ $semester->id }}" @selected((string) $selected === (string) $semester->id)>
            {{ $semester->label }}
        </option>
    @empty
        <option value="" disabled>Belum ada semester.</option>
    @endforelse
</select>