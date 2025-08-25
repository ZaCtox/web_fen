<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clases Académicas</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #444; padding: 5px; text-align: left; vertical-align: top; }
        th { background-color: #eee; }
        .muted { color: #666; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>
    <h2>📚 Reporte de Clases Académicas</h2>

    <table>
        <thead>
            <tr>
                <th>Programa</th>
                <th>Asignatura</th>
                <th>Tipo</th>
                <th>Día</th>
                <th>Horario</th>
                <th>Trimestre</th>
                <th>Año</th>
                <th>Modalidad</th>
                <th>Sala</th>
                <th>Encargado</th>
                <th>Enlace Zoom</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($clases as $clase)
                <tr>
                    <td>{{ $clase->course->magister->nombre ?? 'N/A' }}</td>
                    <td>{{ $clase->course->nombre }}</td>
                    <td>{{ $clase->tipo ? ucfirst($clase->tipo) : '—' }}</td>
                    <td>{{ $clase->dia ?? '—' }}</td>
                    <td class="nowrap">{{ $clase->hora_inicio }} - {{ $clase->hora_fin }}</td>
                    <td>{{ $clase->period->numero ?? '—' }}</td>
                    <td>{{ $clase->period->anio ?? '—' }}</td>
                    <td>{{ $clase->modality ? ucfirst($clase->modality) : '—' }}</td>
                    <td>{{ $clase->room->name ?? 'Sin sala' }}</td>
                    <td>{{ $clase->encargado ?? '—' }}</td>
                    <td>
                        @if(!empty($clase->url_zoom))
                            {{-- En PDF se verá como texto clickeable en algunos visores --}}
                            <span class="muted">{{ $clase->url_zoom }}</span>
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11">No hay clases registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
