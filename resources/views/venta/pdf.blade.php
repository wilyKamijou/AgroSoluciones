<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Ventas</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Ventas</h1>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info">
        <p><strong>Total de ventas:</strong> {{ $ventas->count() }}</p>
        <p><strong>Monto total:</strong> ${{ number_format($ventas->sum('montoTotalVe'), 2) }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Monto Total</th>
                <th>Empleado</th>
                <th>Cliente</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
            @php
                $empleado = $empleados->firstWhere('id_empleado', $venta->id_empleado);
                $cliente = $clientes->firstWhere('id_cliente', $venta->id_cliente);
            @endphp
            <tr>
                <td>{{ $venta->id_venta }}</td>
                <td>{{ date('d/m/Y', strtotime($venta->fechaVe)) }}</td>
                <td>${{ number_format($venta->montoTotalVe, 2) }}</td>
                <td>
                    @if($empleado)
                        {{ $empleado->nombreEm }} {{ $empleado->apellidosEm }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($cliente)
                        {{ $cliente->nombreCl }} {{ $cliente->apellidosCl }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total General: ${{ number_format($ventas->sum('montoTotalVe'), 2) }}
    </div>

    <div class="footer">
        Sistema de Gestión - Generado automáticamente
    </div>
</body>
</html>