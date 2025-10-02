{{-- Editar Curso.blade.php --}}
@section('title', 'Editar Curso')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Editar Curso
        </h2>
    </x-slot>

    <div class="mt-3 p-6 max-w-2xl mx-auto bg-white dark:bg-gray-900 rounded-xl shadow-md">
        <form action="{{ route('courses.update', $course) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('courses.form')
        </form>
    </div>

    @vite(['resources/js/courses/form.js'])
</x-app-layout>

<script>
    window.PERIODS = @json($periods);
</script>
