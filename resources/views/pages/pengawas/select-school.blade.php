<x-layouts.app>
    <x-slot:title>Pilih Sekolah</x-slot:title>

    <div class="flex min-h-[calc(100vh-16rem)] items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-2xl">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pilih Sekolah Pengawasan</h1>
                <p class="mt-3 text-lg text-slate-500">Silakan pilih salah satu sekolah di bawah ini untuk mulai memantau data dan memberikan feedback.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($schools as $school)
                    <form action="{{ route('pengawas.set-school') }}" method="POST">
                        @csrf
                        <input type="hidden" name="school_id" value="{{ $school->id }}">
                        <button type="submit" class="w-full group relative flex flex-col items-center p-8 bg-white border-2 {{ session('pengawas_school_id') == $school->id ? 'border-accent shadow-md ring-1 ring-accent' : 'border-slate-100 hover:border-accent/30 hover:shadow-lg' }} rounded-2xl transition-all duration-300">
                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full {{ session('pengawas_school_id') == $school->id ? 'bg-accent/10' : 'bg-slate-50 group-hover:bg-accent/5' }} transition-colors">
                                @if($school->logo)
                                    <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="h-10 w-10 object-contain">
                                @else
                                    <svg class="h-8 w-8 {{ session('pengawas_school_id') == $school->id ? 'text-accent' : 'text-slate-400 group-hover:text-accent/60' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 text-center">{{ $school->name }}</h3>
                            @if($school->npsn)
                                <p class="mt-1 text-sm text-slate-500 font-mono">NPSN: {{ $school->npsn }}</p>
                            @endif
                            
                            @if(session('pengawas_school_id') == $school->id)
                                <span class="absolute top-4 right-4 flex h-6 w-6 items-center justify-center rounded-full bg-accent text-white">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                            @endif
                            
                            <div class="mt-6 text-sm font-semibold {{ session('pengawas_school_id') == $school->id ? 'text-accent' : 'text-slate-400 group-hover:text-accent' }}">
                                {{ session('pengawas_school_id') == $school->id ? 'Sedang Aktif' : 'Pilih Sekolah' }}
                            </div>
                        </button>
                    </form>
                @endforeach

                @if($schools->isEmpty())
                    <div class="col-span-full p-12 text-center bg-white border-2 border-dashed border-slate-200 rounded-2xl">
                        <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-4 text-sm font-medium text-slate-900">Tidak ada sekolah</h3>
                        <p class="mt-1 text-sm text-slate-500">Anda belum di-assign ke sekolah manapun oleh Super Admin.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
