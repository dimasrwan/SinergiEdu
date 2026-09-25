<x-layouts.app>
    <x-slot:title>Detail Aspirasi</x-slot:title>

    <div class="space-y-6">
        <a href="{{ route('kepala-sekolah.aspirations.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">&larr; Kembali ke daftar</a>

        <x-card>
            <h1 class="text-lg font-bold text-slate-900">{{ $aspiration->title }}</h1>
            <p class="text-xs text-slate-500 mt-1">Oleh: {{ $aspiration->user->name }} | {{ $aspiration->created_at->format('d M Y') }}</p>
            <div class="mt-4 text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">
                {{ $aspiration->content }}
            </div>
        </x-card>

        <!-- Balasan -->
        <x-card>
            <h2 class="text-sm font-bold text-slate-900 mb-4">Tanggapan</h2>
            <div class="space-y-4">
                @foreach($aspiration->responses as $response)
                    <div class="p-4 rounded-xl {{ $response->user->role_id == auth()->user()->role_id ? 'bg-blue-50 border border-blue-100' : 'bg-slate-50 border border-slate-100' }}">
                        <p class="text-xs font-bold text-slate-900">{{ $response->user->name }}</p>
                        <p class="text-xs text-slate-600 mt-1">{{ $response->message }}</p>
                    </div>
                @endforeach
            </div>

            <form action="{{ route('kepala-sekolah.aspirations.store-response', $aspiration) }}" method="POST" class="mt-6 pt-6 border-t border-slate-100">
                @csrf
                <textarea name="message" rows="3" class="w-full text-xs rounded-xl border-slate-200 focus:ring-primary focus:border-primary" placeholder="Tulis tanggapan anda..." required></textarea>
                <button type="submit" class="mt-3 px-4 py-2 text-xs font-bold text-white bg-primary hover:bg-blue-800 rounded-xl">Kirim Tanggapan</button>
            </form>
        </x-card>
    </div>
</x-layouts.app>
