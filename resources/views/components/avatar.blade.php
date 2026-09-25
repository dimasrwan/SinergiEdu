@props(['user', 'size' => 'w-10 h-10', 'textSize' => 'text-sm'])

@php
    $photoUrl = $user->profilePhotoUrl();
    $initials = strtoupper(substr($user->name, 0, 1));
@endphp

@if ($photoUrl)
    <img src="{{ $photoUrl }}" alt="Foto profil {{ $user->name }}" {{ $attributes->merge(['class' => $size . ' rounded-full object-cover border border-slate-200/60 shadow-sm']) }}>
@else
    <div {{ $attributes->merge(['class' => $size . ' rounded-full bg-blue-100 flex items-center justify-center ' . $textSize . ' font-bold text-blue-700 border border-white shadow-sm shrink-0']) }}>
        {{ $initials }}
    </div>
@endif
