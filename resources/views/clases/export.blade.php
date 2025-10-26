<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Clases Académicas</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #aaa;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
        }

        td {
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .nowrap {
            white-space: nowrap;
        }

        .modalidad-presencial {
            background-color: #d1fae5;
            text-align: center;
            font-weight: bold;
        }

        .modalidad-online {
            background-color: #e0f2fe;
            text-align: center;
            font-weight: bold;
        }

        .muted {
            color: #666;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div style="text-align: center; margin-bottom: 10px;">
        {{-- <img src="{{ public_path('images/utalca-logo.png') }}" alt="Logo UTalca" height="60"
            style="margin-right: 20px;"> --}}
        <img src="{{ public_path('images/logo-fen.png') }}" alt="Logo FEN" height="20">
    </div>
    <h2>Reporte de Clases Académicas</h2>

    <table>
        <thead>
            <tr>
                <th>Programa</th>
                <th>Asignatura</th>
                <th>Tipo</th>
                <th>Trimestre</th>
                <th>Año</th>
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
                    <td class="nowrap" style="text-align:center">{{ $clase->period->numero ?? '—' }}</td>
                    <td class="nowrap" style="text-align:center">{{ $clase->period->anio ?? '—' }}</td>
                    <td>{{ $clase->encargado ?? '—' }}</td>
                    <td>
                        @if(!empty($clase->url_zoom))
                            <span class="muted">{{ $clase->url_zoom }}</span>
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding: 10px;">No hay clases registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>


