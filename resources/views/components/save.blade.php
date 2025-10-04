<button 
    {{ $attributes->merge([
        'type' => 'button',
        'class' => 'inline-flex items-center justify-center px-4 py-2 
                    rounded-lg shadow text-sm font-medium transition 
                    transform hover:scale-105 gap-2
                    bg-[#005187] hover:bg-[#4d82bc] text-white'
    ]) }}>
    {{ $slot }}
</button>
