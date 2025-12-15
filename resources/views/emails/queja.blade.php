<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Nueva Queja / Consulta</title>
</head>

<body>
    <h2>Nueva Queja / Consulta Recibida</h2>
    <p><strong>Nombre:</strong> {{ $datos['nombre'] }}</p>
    <p><strong>Email:</strong> {{ $datos['email'] }}</p>
    <p><strong>Asunto:</strong> {{ $datos['asunto'] }}</p>
    <p><strong>Mensaje:</strong></p>
    <p>{{ $datos['mensaje'] }}</p>
</body>git 

</html>