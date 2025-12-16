<!DOCTYPE html>
<html>
<head>
    <title>Nuevo mensaje de contacto</title>
</head>
<body>
    <h2>Nuevo mensaje de contacto desde el sitio web</h2>
    
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Nombre:</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $nombre }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Email:</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $email }}</td>
        </tr>

<tr>
    <td style="padding: 10px; border: 1px solid #ddd;"><strong>Asunto:</strong></td>
    <td style="padding: 10px; border: 1px solid #ddd;">
        @php
            $asuntos = [
                'consulta' => 'Consulta General',
                'cotizacion' => 'Solicitud de Cotización', 
                'tecnica' => 'Consulta Técnica',
                'soporte' => 'Soporte'
            ];
            echo $asuntos[$asunto] ?? $asunto;
        @endphp
    </td>
</tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Mensaje:</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ nl2br($mensaje) }}</td>
        </tr>
    </table>
    
    <p style="margin-top: 20px;">
        <em>Este mensaje fue enviado desde el formulario de contacto de AgroSoluciones BO.</em>
    </p>
</body>
</html>