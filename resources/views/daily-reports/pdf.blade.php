<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario - {{ $report->title }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #005187;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #005187;
            margin: 0;
            font-size: 24px;
        }
        
        .header h2 {
            color: #4d82bc;
            margin: 10px 0 0 0;
            font-size: 18px;
            font-weight: normal;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-section h3 {
            color: #005187;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 5px;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 30%;
            padding: 8px 0;
            color: #4d82bc;
        }
        
        .info-value {
            display: table-cell;
            padding: 8px 0;
        }
        
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #4d82bc;
            margin: 15px 0;
            border-radius: 4px;
        }
        
        .entry {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #fafafa;
        }
        
        .entry-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .entry-number {
            background-color: #005187;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .entry-location {
            color: #4d82bc;
            font-weight: bold;
            font-size: 16px;
        }
        
        .entry-observation {
            background-color: white;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 3px solid #4d82bc;
        }
        
        .image-container {
            text-align: center;
            margin: 20px 0;
        }
        
        .image-container img {
            max-width: 100%;
            max-height: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .no-image {
            color: #999;
            font-style: italic;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 4px;
            text-align: center;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        
        .logo {
            max-width: 150px;
            height: auto;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte Diario</h1>
        <h2>{{ $report->title }}</h2>
        <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <h3>Información del Reporte</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">ID del Reporte:</div>
                <div class="info-value">#{{ $report->id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha del Reporte:</div>
                <div class="info-value">{{ $report->report_date->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Creado por:</div>
                <div class="info-value">{{ $report->user->name ?? 'Usuario no disponible' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Número de Observaciones:</div>
                <div class="info-value">{{ $report->entries->count() }} {{ $report->entries->count() === 1 ? 'observación' : 'observaciones' }}</div>
            </div>
        </div>
    </div>

    @if($report->summary)
    <div class="info-section">
        <h3>Resumen General</h3>
        <div class="summary">
            {{ $report->summary }}
        </div>
    </div>
    @endif

    <div class="info-section">
        <h3>Observaciones del Día</h3>
        
        @foreach($report->entries as $index => $entry)
        <div class="entry">
            <div class="entry-header">
                <div class="entry-number">Observación #{{ $index + 1 }}</div>
                <div class="entry-location">{{ $entry->ubicacion_completa }}</div>
            </div>
            
            <div class="entry-observation">
                <strong>Observación:</strong><br>
                {{ $entry->observation }}
            </div>
            
            @if($entry->tiene_foto)
            <div class="image-container">
                <img src="{{ $entry->photo_url }}" alt="Evidencia fotográfica de la observación #{{ $index + 1 }}">
                <p style="margin-top: 10px; font-size: 12px; color: #666;">Evidencia fotográfica</p>
            </div>
            @else
            <div class="no-image">
                No se adjuntó evidencia fotográfica a esta observación.
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="footer">
        <p>Este documento fue generado automáticamente por el Sistema de Gestión FEN</p>
        <p>Universidad de Talca - Facultad de Economía y Negocios</p>
        <p>Página 1 de 1</p>
    </div>
</body>
</html>



