@props(['title', 'description' => null])

<div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-slate-200 pb-5 mb-6 gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">{{ $title }}</h1>
        @if($description)
            <p class="mt-2 text-sm text-slate-500 max-w-2xl">{{ $description }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div class="flex items-center gap-3 w-full sm:w-auto">
            {{ $actions }}
        </div>
    @endif
</div>
