<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Bitácora de Incidencias</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .resumen {
            font-size: 13px;
            margin-top: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div style="text-align: center; margin-bottom: 10px;">
        <img src="{{ public_path('images/utalca-logo.png') }}" alt="Logo UTalca" height="60"
            style="margin-right: 20px;">
        <img src="{{ public_path('images/logo-fen.png') }}" alt="Logo FEN" height="60">
    </div>
    <h1>Bitácora de Incidencias</h1>
    <p style="text-align: center; font-size: 11px; color: #666;">
        Generado por {{ $usuario->name }} | Fecha: {{ $fechaActual }}
    </p>
    <p>Fecha de exportación: {{ now()->format('d/m/Y H:i') }}</p>


    @php
        use App\Models\Room;
        use App\Models\Period;

        $periodo = request('period_id') ? Period::find(request('period_id')) : null;
        $sala = request('room_id') ? Room::find(request('room_id')) : null;
        $usuario = auth()->user();
        $fechaActual = \Carbon\Carbon::now()->format('d/m/Y H:i');

        $total = $incidencias->count();
        $pendientes = $incidencias->where('estado', 'pendiente')->count();
        $resueltas = $incidencias->where('estado', 'resuelta')->count();
    @endphp


    @if(request()->filled('anio') || $periodo || $sala || request()->filled('estado') || request()->filled('historico'))
        <div class="resumen">
            <strong>Filtros aplicados:</strong><br>
            @if(request('anio')) • Año: {{ request('anio') }}<br> @endif
            @if($periodo) • Período: {{ $periodo->nombre_completo }}<br> @endif
            @if($sala) • Sala: {{ $sala->name }}<br> @endif
            @if(request('estado')) • Estado: {{ ucfirst(request('estado')) }}<br> @endif
            @if(request('historico')) • Incluyendo datos históricos<br> @endif
        </div>
    @endif

    @if($total > 0)
        <div class="resumen">
            <strong>Resumen de incidencias exportadas:</strong><br>
            • Total: {{ $total }}<br>
            • Pendientes: {{ $pendientes }}<br>
            • Resueltas: {{ $resueltas }}
        </div>
    @endif

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
                    <td>{{ $incidencia->room->name ?? 'Sin sala' }}</td>
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