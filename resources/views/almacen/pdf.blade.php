<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Almacenes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .info {
            margin-bottom: 15px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Almacenes</h1>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info">
        <p><strong>Total de almacenes:</strong> {{ $almacens->count() }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Dirección</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($almacens as $almacen)
            <tr>
                <td><strong>{{ $almacen->id_almacen }}</strong></td>
                <td>{{ $almacen->nombreAl }}</td>
                <td>{{ $almacen->descripcionAl }}</td>
                <td>{{ $almacen->direccionAl }}</td>
                <td>
                    <span class="badge badge-success">ACTIVO</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total de Almacenes: {{ $almacens->count() }}
    </div>

    <div class="footer">
        Sistema de Gestión - Generado automáticamente
    </div>
</body>
</html>