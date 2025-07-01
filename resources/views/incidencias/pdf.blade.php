<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bitácora de Incidencias</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f3f3f3;
        }
        h1 {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <h1>Bitácora de Incidencias</h1>

    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Sala</th>
                <th>Estado</th>
                <th>Registrado por</th>
                <th>Fecha</th>
                <th>Resuelta el</th>
            </tr>
        </thead>
        <tbody>
            @forelse($incidencias as $incidencia)
                <tr>
                    <td>{{ $incidencia->titulo }}</td>
                    <td>{{ $incidencia->sala }}</td>
                    <td>{{ ucfirst($incidencia->estado) }}</td>
                    <td>{{ $incidencia->user->name ?? 'N/D' }}</td>
                    <td>{{ $incidencia->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $incidencia->resuelta_en ? $incidencia->resuelta_en->format('d/m/Y H:i') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No hay registros para exportar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
