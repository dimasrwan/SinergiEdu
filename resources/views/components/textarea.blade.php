@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-slate-300 focus:border-accent focus:ring-accent rounded-lg shadow-sm w-full transition-colors disabled:bg-slate-50 disabled:text-slate-500 sm:text-sm']) !!}>{{ $slot }}</textarea>
