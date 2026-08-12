@props(['disabled' => false])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-accent focus:ring-accent/20 focus:bg-white transition duration-200 disabled:opacity-75 disabled:bg-slate-100 disabled:text-slate-500']) !!}>
    {{ $slot }}
</select>
