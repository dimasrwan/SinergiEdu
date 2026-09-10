<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center min-h-[44px] sm:min-h-0 px-4 py-2 bg-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wider hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-primary/20 focus:ring-offset-2 active:scale-[0.98] transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
