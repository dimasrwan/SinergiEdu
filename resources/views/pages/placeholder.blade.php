<x-layouts.app>
    <x-slot:title>Halaman Segera Hadir</x-slot:title>

    <div class="w-full h-full flex flex-col items-center justify-center py-20 text-center">
        <div class="bg-blue-50 text-primary p-4 rounded-full mb-6">
            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Halaman Sedang Dalam Pengembangan</h2>
        <p class="text-slate-500 max-w-md">Fitur ini merupakan bagian dari roadmap SinergiEdu dan akan segera tersedia pada pembaruan berikutnya.</p>
        
        <x-button variant="primary" href="{{ url()->previous() }}" class="mt-8">
            Kembali
        </x-button>
    </div>
</x-layouts.app>
