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
        
        .entry-details {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .entry-detail-row {
            display: table-row;
        }
        
        .entry-detail-label {
            display: table-cell;
            font-weight: bold;
            width: 20%;
            padding: 5px 10px 5px 0;
            color: #4d82bc;
            font-size: 12px;
        }
        
        .entry-detail-value {
            display: table-cell;
            padding: 5px 0;
            font-size: 12px;
        }
        
        .severity-indicator {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            color: white;
        }
        
        .severity-normal { background-color: #4DBCC6; }
        .severity-leve { background-color: #8B8232; }
        .severity-moderado { background-color: #FFCC00; color: #000; }
        .severity-fuerte { background-color: #FF6600; }
        .severity-critico { background-color: #FF0000; }
        
        .task-section {
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 3px solid #ffc107;
        }
        
        .severity-icon {
            width: 20px;
            height: 20px;
            display: inline-block;
            margin-right: 8px;
            vertical-align: middle;
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

        <!-- Escala de Severidad -->
        <div class="info-section">
            <h3>Escala de Severidad</h3>
            
            {{-- Tabla de Escala de Severidad --}}
            <div style="margin: 20px 0; background-color: #f8f9fa; border-radius: 8px; padding: 20px; border: 1px solid #e0e0e0;">
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                    <thead>
                        <tr style="background-color: #005187; color: white;">
                            <th style="padding: 12px; text-align: center; border: 1px solid #003d6b; font-size: 14px; font-weight: bold;">Escala</th>
                            <th style="padding: 12px; text-align: center; border: 1px solid #003d6b; font-size: 14px; font-weight: bold;">Nivel</th>
                            <th style="padding: 12px; text-align: center; border: 1px solid #003d6b; font-size: 14px; font-weight: bold;">Descripción</th>
                    
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color: #4DBCC6; color: white;">
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd; font-weight: bold;">1-2</td>
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd; font-weight: bold;">Normal</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">Condiciones óptimas, sin problemas</td>
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd;">
                                
                            </td>
                        </tr>
                        <tr style="background-color: #8B8232; color: white;">
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd; font-weight: bold;">3-4</td>
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd; font-weight: bold;">Leve</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">Problemas menores, atención básica</td>
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd;">
                               
                            </td>
                        </tr>
                        <tr style="background-color: #FFCC00; color: #000;">
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd; font-weight: bold;">5-6</td>
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd; font-weight: bold;">Moderado</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">Problemas que requieren atención</td>
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd;">
                               
                            </td>
                        </tr>
                        <tr style="background-color: #FF6600; color: white;">
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd; font-weight: bold;">7-8</td>
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd; font-weight: bold;">Fuerte</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">Problemas importantes, acción inmediata</td>
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd;">
                               
                            </td>
                        </tr>
                        <tr style="background-color: #FF0000; color: white;">
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd; font-weight: bold;">9-10</td>
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd; font-weight: bold;">Crítico</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">Situación crítica, intervención urgente</td>
                            <td style="padding: 10px; text-align: center; border: 1px solid #ddd;">
                               
                            </td>
                        </tr>
                    </tbody>
                </table>
                

            </div>
        </div>

    <div class="info-section">
        <h3>Observaciones del Día</h3>
        
        {{-- Tabla de Observaciones --}}
        <div style="margin: 20px 0; background-color: #f8f9fa; border-radius: 8px; padding: 20px; border: 1px solid #e0e0e0;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr style="background-color: #005187; color: white;">
                        <th style="padding: 12px; text-align: center; border: 1px solid #003d6b; font-size: 14px; font-weight: bold;">#</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #003d6b; font-size: 14px; font-weight: bold;">Ubicación</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #003d6b; font-size: 14px; font-weight: bold;">Horario</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #003d6b; font-size: 14px; font-weight: bold;">Escala</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #003d6b; font-size: 14px; font-weight: bold;">Programa/Área</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #003d6b; font-size: 14px; font-weight: bold;">Evidencia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report->entries as $index => $entry)
                    <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f8f9fa' }}; border-bottom: 1px solid #e0e0e0;">
                        <td style="padding: 12px; text-align: center; border: 1px solid #ddd; font-weight: bold; color: #005187;">{{ $index + 1 }}</td>
                        <td style="padding: 12px; border: 1px solid #ddd; font-weight: bold; color: #4d82bc;">{{ $entry->ubicacion_completa }}</td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #ddd;">{{ $entry->hora ?? 'N/A' }}</td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #ddd;">
                            @if($entry->escala)
                                <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <div style="width: 24px; height: 24px; background-color: {{ $entry->color_escala }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px; border: 2px solid white;">
                                        {{ $entry->escala }}
                                    </div>
                                    <span style="font-size: 12px; font-weight: bold;">{{ $entry->nivel_severidad }}</span>
                                </div>
                            @else
                                <span style="color: #999; font-style: italic;">N/A</span>
                            @endif
                        </td>
                        <td style="padding: 12px; border: 1px solid #ddd; font-size: 12px;">
                            @if($entry->programa || $entry->area)
                                <div style="margin-bottom: 4px;">
                                    @if($entry->programa)<strong>Programa:</strong> {{ $entry->programa }}@endif
                                </div>
                                @if($entry->area)<div><strong>Área:</strong> {{ $entry->area }}</div>@endif
                            @else
                                <span style="color: #999; font-style: italic;">N/A</span>
                            @endif
                        </td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #ddd;">
                            @if($entry->tiene_foto)
                                <div style="color: #28a745; font-weight: bold;">Sí</div>
                            @else
                                <div style="color: #999;">No</div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Detalles de cada Observación --}}
        @foreach($report->entries as $index => $entry)
        <div class="entry" style="page-break-inside: avoid;">
            <div class="entry-header">
                <div class="entry-number">Observación #{{ $index + 1 }}</div>
                <div class="entry-location">{{ $entry->ubicacion_completa }}</div>
            </div>
            
            <div class="entry-observation">
                <strong>Observación:</strong><br>
                {{ $entry->observation }}
            </div>
            
            @if($entry->tarea)
            <div class="task-section">
                <strong>Tarea:</strong><br>
                {{ $entry->tarea }}
            </div>
            @endif
            
            @if($entry->tiene_foto)
            <div class="image-container">
                <img src="{{ $entry->photo_url }}" alt="Evidencia fotográfica de la observación #{{ $index + 1 }}">
                <p style="margin-top: 10px; font-size: 12px; color: #666;">Evidencia fotográfica</p>
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



