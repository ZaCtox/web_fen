<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Bitácora - {{ $bitacora->titulo }}</title>
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
        
        .description {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #4d82bc;
            margin: 15px 0;
            border-radius: 4px;
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
        
        .image-container {
            text-align: center;
            margin: 20px 0;
        }
        
        .image-container img {
            max-width: 100%;
            max-height: 400px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .no-image {
            color: #999;
            font-style: italic;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Bitácora</h1>
        <h2>{{ $bitacora->titulo }}</h2>
        <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <h3>Información del Reporte</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">ID del Reporte:</div>
                <div class="info-value">#{{ $bitacora->id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Creación:</div>
                <div class="info-value">{{ $bitacora->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Creado por:</div>
                <div class="info-value">{{ $bitacora->user->name ?? 'Usuario no disponible' }}</div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <h3>Ubicación</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Tipo de Ubicación:</div>
                <div class="info-value">{{ $bitacora->tipo_ubicacion }}</div>
            </div>
            @if($bitacora->ubicacion_completa)
            <div class="info-row">
                <div class="info-label">Ubicación Específica:</div>
                <div class="info-value">{{ $bitacora->ubicacion_completa }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="info-section">
        <h3>Descripción del Reporte</h3>
        <div class="description">
            {{ $bitacora->descripcion }}
        </div>
    </div>

    @if($bitacora->tiene_foto)
    <div class="info-section">
        <h3>Evidencia Fotográfica</h3>
        <div class="image-container">
            <img src="{{ $bitacora->foto_url }}" alt="Evidencia fotográfica del reporte">
        </div>
    </div>
    @else
    <div class="info-section">
        <h3>Evidencia Fotográfica</h3>
        <div class="no-image">
            No se adjuntó evidencia fotográfica a este reporte.
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Este documento fue generado automáticamente por el Sistema de Gestión FEN</p>
        <p>Universidad de Talca - Facultad de Economía y Negocios</p>
        <p>Página 1 de 1</p>
    </div>
</body>
</html>


