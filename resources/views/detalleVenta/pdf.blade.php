<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Detalles de Venta</title>
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
            padding: 6px;
            text-align: left;
            font-size: 11px;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .totals {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .total-item {
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Detalles de Venta</h1>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info">
        <p><strong>Total de detalles:</strong> {{ $detalleVs->count() }}</p>
        <p><strong>Total productos vendidos:</strong> {{ $detalleVs->sum('cantidadDV') }}</p>
        <p><strong>Monto total:</strong> ${{ number_format($detalleVs->sum(function($item) { return $item->precioDv * $item->cantidadDV; }), 2) }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID Detalle</th>
                <th>Venta ID</th>
                <th>Producto</th>
                <th>Almacén</th>
                <th>Precio Unit.</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalleVs as $detalle)
            @php
                $producto = $productos->firstWhere('id_producto', $detalle->id_producto);
                $almacen = $almacenes->firstWhere('id_almacen', $detalle->id_almacen);
                $venta = $ventas->firstWhere('id_venta', $detalle->id_venta);
                $subtotal = $detalle->precioDv * $detalle->cantidadDV;
            @endphp
            <tr>
                <td>{{ $detalle->id_venta }}-{{ $detalle->id_producto }}-{{ $detalle->id_almacen }}</td>
                <td>Venta #{{ $detalle->id_venta }}</td>
                <td>
                    @if($producto)
                        {{ $producto->nombrePr }}
                    @else
                        <span style="color: #dc3545;">Producto no encontrado</span>
                    @endif
                </td>
                <td>
                    @if($almacen)
                        {{ $almacen->nombreAl }}
                    @else
                        <span style="color: #dc3545;">Almacén no encontrado</span>
                    @endif
                </td>
                <td class="text-right">${{ number_format($detalle->precioDv, 2) }}</td>
                <td class="text-right">{{ $detalle->cantidadDV }}</td>
                <td class="text-right">${{ number_format($subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="total-item"><strong>Total de registros:</strong> {{ $detalleVs->count() }}</div>
        <div class="total-item"><strong>Total productos vendidos:</strong> {{ $detalleVs->sum('cantidadDV') }}</div>
        <div class="total-item"><strong>Monto total general:</strong> ${{ number_format($detalleVs->sum(function($item) { return $item->precioDv * $item->cantidadDV; }), 2) }}</div>
    </div>

    <div class="footer">
        Sistema de Gestión - Generado automáticamente
    </div>
</body>
</html>