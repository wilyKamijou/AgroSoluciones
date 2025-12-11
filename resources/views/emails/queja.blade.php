<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Nueva Queja / Consulta</title>
</head>

<body>
    <h2>Nueva Queja / Consulta Recibida</h2>
    <p><strong>Nombre:</strong> {{ $data['nombre'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Asunto:</strong> {{ $data['asunto'] }}</p>
    <p><strong>Mensaje:</strong></p>
    <p>{{ $data['mensaje'] }}</p>
</body>

</html>