@props(['disabled' => false])

<input type="checkbox" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded border-slate-300 text-accent shadow-sm focus:ring-accent transition-colors disabled:bg-slate-50 disabled:cursor-not-allowed w-4 h-4 mt-0.5']) !!}>
