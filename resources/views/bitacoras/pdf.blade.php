<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe Bitácora</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Informe de Bitácora</h1>
    <p><strong>Lugar:</strong> {{ $bitacora->lugar }}</p>
    <p><strong>Descripción:</strong> {{ $bitacora->descripcion }}</p>

    @if($bitacora->foto_url)
        <p><img src="{{ $bitacora->foto_url }}" style="max-width:400px;"></p>
    @endif
    <p><small>Generado en: {{ now() }}</small></p>
</body>
</html>
