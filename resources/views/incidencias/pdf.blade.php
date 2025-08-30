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

        .estado {
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div style="text-align: center; margin-bottom: 10px;">
        {{-- <img src="{{ public_path('images/utalca-logo.png') }}" alt="Logo UTalca" height="60"
            style="margin-right: 20px;"> --}}
        <img src="{{ public_path('images/logo-fen.png') }}" alt="Logo FEN" height="20">
    </div>

    <h1>Bitácora de Incidencias</h1>

    <p style="text-align: center; font-size: 11px; color: #666;">
        Generado por {{ $usuario->name }} | Fecha: {{ $fechaActual }}
    </p>
    @php
        use App\Models\Room;
        use App\Models\Period;

        $periodo = request('period_id') ? Period::find(request('period_id')) : null;
        $sala = request('room_id') ? Room::find(request('room_id')) : null;

        $total = $incidencias->count();
        $pendientes = $incidencias->where('estado', 'pendiente')->count();
        $resueltas = $incidencias->where('estado', 'resuelta')->count();
        $enRevision = $incidencias->where('estado', 'en_revision')->count();
        $noResueltas = $incidencias->where('estado', 'no_resuelta')->count();
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
            • En revisión: {{ $enRevision }}<br>
            • Resueltas: {{ $resueltas }}<br>
            • No resueltas: {{ $noResueltas }}
        </div>
    @endif

    @php
        $estadoIconos = [
            'pendiente' => public_path('images/estados/pendiente.png'),
            'en_revision' => public_path('images/estados/en_revision.png'),
            'resuelta' => public_path('images/estados/resuelta.png'),
            'no_resuelta' => public_path('images/estados/no_resuelta.png'),
        ];
    @endphp

    <table>
        <thead>
            <tr>
                <th style="text-align:center;">Título</th>
                <th style="text-align:center;">Sala</th>
                <th style="text-align:center;">Estado</th>
                <th style="text-align:center;">Ticket</th>
                <th style="text-align:center;">Registrado por</th>
                <th style="text-align:center;">Fecha</th>
                <th style="text-align:center;">Resuelta el</th>
                <th style="text-align:center;">Resuelta por</th>
            </tr>
        </thead>
        <tbody>
            @forelse($incidencias as $i)
                <tr>
                    <td>{{ $i->titulo }}</td>
                    <td>{{ $i->room->name ?? 'Sin sala' }}</td>
                    <td style="text-align:center;">
                        @if(isset($estadoIconos[$i->estado]))
                            <img src="{{ $estadoIconos[$i->estado] }}" width="16" height="16"
                                alt="{{ ucfirst(str_replace('_', ' ', $i->estado)) }}">
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $i->nro_ticket ?? '—' }}</td>
                    <td>{{ $i->user->name ?? 'N/D' }}</td>
                    <td>{{ $i->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $i->resuelta_en ? $i->resuelta_en->format('d/m/Y H:i') : '—' }}</td>
                    <td>{{ $i->resolvedBy->name ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">No hay registros para exportar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>