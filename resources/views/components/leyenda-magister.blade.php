<div class="flex flex-wrap gap-4 mb-4">
    @foreach(\App\Models\Magister::orderBy('nombre')->get() as $magister)
        <div class="flex items-center gap-2">
            <span class="w-4 h-4 inline-block rounded-full" style="background-color: {{ $magister->color ?? '#6b7280' }}"></span>
            <span class="text-sm text-gray-800 dark:text-gray-200">{{ $magister->nombre }}</span>
        </div>
    @endforeach
</div>
