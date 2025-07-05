<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba Cloudinary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5 bg-light">
    <div class="container">
        <h2 class="mb-4">Subida de imagen a Cloudinary</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            <img src="{{ session('url') }}" class="img-fluid rounded shadow mt-3" alt="Imagen subida">
        @endif

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('cloudinary.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="imagen" class="form-label">Selecciona una imagen</label>
                <input type="file" class="form-control" name="imagen" id="imagen" required>
            </div>
            <button type="submit" class="btn btn-primary">Subir imagen</button>
        </form>
    </div>
</body>
</html>
