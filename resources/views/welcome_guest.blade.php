<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido - Web_FEN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .login-box {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h1 class="mb-4">Bienvenido a Web_FEN</h1>
    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Ingresar</a>
</div>

</body>
</html>
