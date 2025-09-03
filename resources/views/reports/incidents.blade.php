<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Incidencias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
        }
        .filters {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .filters h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .filter-item {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .status-pendiente { color: #e74c3c; font-weight: bold; }
        .status-en_progreso { color: #f39c12; font-weight: bold; }
        .status-resuelta { color: #27ae60; font-weight: bold; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Reporte de Incidencias</div>
        <div class="subtitle">Sistema de Gestión Académica</div>
    </div>

    @if(!empty($filters))
    <div class="filters">
        <h3>Filtros Aplicados:</h3>
        @if(isset($filters['estado']))
            <div class="filter-item"><strong>Estado:</strong> {{ ucfirst($filters['estado']) }}</div>
        @endif
        @if(isset($filters['room_id']))
            <div class="filter-item"><strong>Sala ID:</strong> {{ $filters['room_id'] }}</div>
        @endif
        @if(isset($filters['start_date']))
            <div class="filter-item"><strong>Fecha Inicio:</strong> {{ $filters['start_date'] }}</div>
        @endif
        @if(isset($filters['end_date']))
            <div class="filter-item"><strong>Fecha Fin:</strong> {{ $filters['end_date'] }}</div>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Ticket</th>
                <th>Título</th>
                <th>Sala</th>
                <th>Estado</th>
                <th>Usuario</th>
                <th>Fecha Creación</th>
                <th>Fecha Resolución</th>
            </tr>
        </thead>
        <tbody>
            @forelse($incidents as $incident)
            <tr>
                <td>{{ $incident->nro_ticket }}</td>
                <td>{{ $incident->titulo }}</td>
                <td>{{ $incident->room->name ?? 'N/A' }}</td>
                <td class="status-{{ $incident->estado }}">
                    {{ ucfirst(str_replace('_', ' ', $incident->estado)) }}
                </td>
                <td>{{ $incident->user->name ?? 'N/A' }}</td>
                <td>{{ $incident->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if($incident->resuelta_en)
                        {{ $incident->resuelta_en->format('d/m/Y H:i') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">No se encontraron incidencias con los filtros aplicados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado el {{ $generated_at }}</p>
        <p>Total de incidencias: {{ $incidents->count() }}</p>
    </div>
</body>
</html>
