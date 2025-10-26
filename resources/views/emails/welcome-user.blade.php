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
                    <!-- Header con logo -->
                    <tr>
                        <td style="background-color: #005187; color: #ffffff; text-align: center; padding: 30px 20px;">
                            <img src="{{ asset('images/FEN1.png') }}" alt="FEN Logo" style="max-width: 200px; height: auto; margin-bottom: 15px;">
                            <h1 style="margin:0; font-size: 24px; font-weight: 300;">¡Bienvenid@s a la Escuela de Postgrados FEN!</h1>
                            <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">Facultad de Economía y Negocios - Universidad de Talca</p>
                        </td>
                    </tr>
                    <!-- Contenido principal -->
                    <tr>
                        <td style="padding: 40px 30px; color: #333333; line-height: 1.6;">
                            <p style="font-size: 16px; margin-bottom: 20px;">Hola <strong>{{ $name }}</strong>,</p>
                            
                            <p style="margin-bottom: 20px;">¡Bienvenid@ a la plataforma de gestión académica de la Escuela de Postgrado FEN! Tu cuenta ha sido creada exitosamente.</p>
                            
                            <div style="background-color: #f8f9fa; border-left: 4px solid #005187; padding: 20px; margin: 25px 0; border-radius: 4px;">
                                <h3 style="margin: 0 0 15px 0; color: #005187; font-size: 18px;">📋 Datos de Acceso</h3>
                                <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                    <tr>
                                        <td style="padding: 8px 0; font-weight: bold; color: #555; width: 120px;">Correo:</td>
                                        <td style="padding: 8px 0; color: #333;">{{ $email }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; font-weight: bold; color: #555;">Contraseña:</td>
                                        <td style="padding: 8px 0; color: #333; font-family: monospace; background-color: #e9ecef; padding: 4px 8px; border-radius: 3px;">{{ $password }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 20px 0; border-radius: 4px;">
                                <p style="margin: 0; color: #856404; font-size: 14px;">
                                    <strong>🔒 Importante:</strong> Por seguridad, te recomendamos cambiar tu contraseña después del primer inicio de sesión.
                                </p>
                            </div>

                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ url('/login') }}"
                                    style="display: inline-block; padding: 15px 30px; background-color: #005187; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px; transition: background 0.3s;">
                                    🚀 Iniciar Sesión
                                </a>
                            </div>

                            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                                <p style="font-size: 14px; color: #666; margin: 0;">
                                    Si tienes alguna pregunta o necesitas ayuda, no dudes en contactar al equipo de soporte.
                                </p>
                            </div>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #4d82bc; color: #ffffff; text-align: center; padding: 20px; font-size: 12px;">
                            <p style="margin: 0 0 5px 0;">&copy; {{ date('Y') }} Postgrado FEN - Universidad de Talca</p>
                            <p style="margin: 0; opacity: 0.8;">Facultad de Economía y Negocios | Todos los derechos reservados</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>


