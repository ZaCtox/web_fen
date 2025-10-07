{{-- Componente HCI Breadcrumb - Ley de Jakob --}}
<nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm mb-6" aria-label="Breadcrumb">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <ol class="flex items-center space-x-2 text-sm">
            @foreach($items as $item)
                <li class="flex items-center">
                    @if($loop->last)
                        <span class="text-gray-800 dark:text-gray-200 font-medium flex items-center">
                            @if(isset($item['icon']))
                                <span class="mr-2">{{ $item['icon'] }}</span>
                            @endif
                            {{ $item['label'] }}
                        </span>
                    @else
                        <a href="{{ $item['url'] }}" 
                           class="text-gray-600 dark:text-gray-400 hover:text-[#4d82bc] dark:hover:text-[#84b6f4] 
                                  flex items-center transition-colors duration-200">
                            @if(isset($item['icon']))
                                <span class="mr-2">{{ $item['icon'] }}</span>
                            @endif
                            {{ $item['label'] }}
                        </a>
                        <svg class="w-4 h-4 mx-2 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</nav>
