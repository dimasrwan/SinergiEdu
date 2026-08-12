@props(['headers' => []])

<div class="bg-surface border border-slate-200 rounded-xl shadow-sm overflow-hidden w-full">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            @if(count($headers) > 0)
                <thead class="bg-slate-50 text-slate-500 font-medium text-xs uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        @foreach($headers as $header)
                            <th class="px-6 py-4 whitespace-nowrap">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody class="divide-y divide-slate-100">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
