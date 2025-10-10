{{-- Skeleton Screen para Tablas - Efecto Doherty --}}
@props(['rows' => 5, 'columns' => 4])

<div class="animate-pulse">
    <table class="min-w-full">
        <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
                @for($i = 0; $i < $columns; $i++)
                    <th class="px-4 py-3">
                        <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-20"></div>
                    </th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @for($r = 0; $r < $rows; $r++)
                <tr class="border-b border-gray-200 dark:border-gray-600">
                    @for($c = 0; $c < $columns; $c++)
                        <td class="px-4 py-3">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded {{ $c === 0 ? 'w-32' : 'w-24' }}"></div>
                        </td>
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>
</div>



