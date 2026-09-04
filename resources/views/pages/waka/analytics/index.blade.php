<x-layouts.app>
    <x-slot:title>Analitik Akademik</x-slot:title>
    <div class="space-y-6">
        <h1 class="text-2xl font-bold text-slate-900">Analitik Akademik</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-card>
                <h2 class="font-bold">Trend Performa</h2>
                <p>Data: {{ implode(', ', $performanceTrend) }}</p>
            </x-card>
            <x-card>
                <h2 class="font-bold">Perbandingan Mapel</h2>
                <ul>
                    @foreach($subjectComparison as $sub)
                        <li>{{ $sub['name'] }}: {{ $sub['avg'] }}</li>
                    @endforeach
                </ul>
            </x-card>
        </div>
    </div>
</x-layouts.app>