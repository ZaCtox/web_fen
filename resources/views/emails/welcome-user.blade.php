<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Bienvenido a la plataforma</title>
</head>

<body
    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; margin:0; padding:0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="background-color: #005187; color: #ffffff; text-align: center; padding: 20px;">
                            <h1 style="margin:0; font-size: 24px;">Bienvenido a Postgrado FEN</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px; color: #333333;">
                            <p>Hola <strong>{{ $name }}</strong>,</p>
                            <p>Tu cuenta ha sido creada en la plataforma. A continuación te dejamos tus datos de acceso:
                            </p>
                            <table cellpadding="0" cellspacing="0" style="margin: 20px 0;">
                                <tr>
                                    <td style="padding: 5px 10px; font-weight: bold;">Correo:</td>
                                    <td style="padding: 5px 10px;">{{ $email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 10px; font-weight: bold;">Contraseña:</td>
                                    <td style="padding: 5px 10px;">{{ $password }}</td>
                                </tr>
                            </table>

                            <p>Por favor, <strong>cambia tu contraseña después de iniciar sesión</strong> para mantener
                                tu cuenta segura.</p>

                            <a href="{{ url('/login') }}"
                                style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #005187; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; transition: background 0.3s;">
                                Iniciar Sesión
                            </a>

                        </td>
                    </tr>
                    <tr>
                        <td
                            style="background-color: #4d82bc; color: #ffffff; text-align: center; padding: 15px; font-size: 12px;">
                            &copy; {{ date('Y') }} Postgrado FEN. Todos los derechos reservados.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>