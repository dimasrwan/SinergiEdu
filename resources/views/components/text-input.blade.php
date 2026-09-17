@props(['disabled' => false, 'type' => 'text'])

@if ($type === 'date')
    <x-date-picker :disabled="$disabled" {{ $attributes->except(['type']) }} />
@else
    <input {{ $disabled ? 'disabled' : '' }} type="{{ $type }}" {!! $attributes->merge(['class' => 'w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-accent focus:ring-accent/20 focus:bg-white transition duration-200 disabled:opacity-75 disabled:bg-slate-100 disabled:text-slate-500']) !!}>
@endif
