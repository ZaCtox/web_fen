<button 
    {{ $attributes->merge([
        'class' => 'inline-flex items-center px-4 py-2 rounded shadow 
                    bg-[var(--color-utalca-primary)] 
                    dark:bg-[var(--color-utalca-secondary)] 
                    text-white dark:text-white 
                    hover:bg-blue-800 dark:hover:bg-red-500 
                    font-semibold transition'
    ]) }}>
    {{ $slot }}
</button>



