@extends('layouts.guest')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 space-y-6">
    <h1 class="text-3xl font-bold text-center text-gray-800 dark:text-white">Calendario Académico</h1>

    <div id="calendar" class="bg-white dark:bg-gray-800 p-4 rounded shadow"></div>

    <div class="mt-10">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Salas Disponibles</h2>
        <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($salas as $sala)
                <li class="bg-gray-100 dark:bg-gray-700 p-4 rounded shadow text-gray-800 dark:text-white">
                    <strong>{{ $sala->name }}</strong><br>
                    <small>{{ $sala->location }}</small>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="text-center mt-8">
        <a href="{{ route('login') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded text-lg font-semibold">
            Ingresar
        </a>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: false,
            height: 'auto',
            editable: false,
            allDaySlot: false,
            slotMinTime: "08:00:00",
            slotMaxTime: "20:00:00",
            events: @json($eventos ?? [])
        });
        calendar.render();
    });
</script>
@endsection
