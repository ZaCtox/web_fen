<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clases Académicas</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 5px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>📚 Reporte de Clases Académicas</h2>
    <table>
        <thead>
            <tr>
                <th>Magíster</th>
                <th>Asignatura</th>
                <th>Periodo</th>
                <th>Día</th>
                <th>Horario</th>
                <th>Modalidad</th>
                <th>Sala</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($clases as $clase)
                <tr>
                    <td>{{ $clase->course->magister->nombre ?? 'N/A' }}</td>
                    <td>{{ $clase->course->nombre }}</td>
                    <td>{{ $clase->period->nombre_completo }}</td>
                    <td>{{ $clase->dia }}</td>
                    <td>{{ $clase->hora_inicio }} - {{ $clase->hora_fin }}</td>
                    <td>{{ ucfirst($clase->modality) }}</td>
                    <td>{{ $clase->room->name ?? 'Sin sala' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No hay clases registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
