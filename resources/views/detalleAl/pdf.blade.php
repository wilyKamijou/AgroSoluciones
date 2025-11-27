<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Detalles de Almacén</title>
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
        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Detalles de Almacén</h1>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info">
        <p><strong>Total de registros:</strong> {{ $detalles->count() }}</p>
        <p><strong>Stock total en almacenes:</strong> {{ $detalles->sum('stock') }}</p>
        <p><strong>Almacenes con stock:</strong> {{ $detalles->groupBy('id_almacen')->count() }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID Detalle</th>
                <th>Producto</th>
                <th>Almacén</th>
                <th class="text-center">Stock</th>
                <th>Estado Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $detalle)
            @php
                $producto = $productos->firstWhere('id_producto', $detalle->id_producto);
                $almacen = $almacens->firstWhere('id_almacen', $detalle->id_almacen);
                
                // Determinar estado del stock
                if ($detalle->stock > 20) {
                    $estado = 'ALTO';
                    $claseBadge = 'badge-success';
                } elseif ($detalle->stock > 5) {
                    $estado = 'MEDIO';
                    $claseBadge = 'badge-warning';
                } else {
                    $estado = 'BAJO';
                    $claseBadge = 'badge-danger';
                }
            @endphp
            <tr>
                <td>{{ $detalle->id_producto }}-{{ $detalle->id_almacen }}</td>
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
                <td class="text-center">
                    <strong>{{ $detalle->stock }}</strong>
                </td>
                <td>
                    <span class="badge {{ $claseBadge }}">{{ $estado }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="total-item"><strong>Total de registros:</strong> {{ $detalles->count() }}</div>
        <div class="total-item"><strong>Stock total en sistema:</strong> {{ $detalles->sum('stock') }}</div>
        <div class="total-item"><strong>Almacenes diferentes:</strong> {{ $detalles->groupBy('id_almacen')->count() }}</div>
        <div class="total-item"><strong>Productos diferentes:</strong> {{ $detalles->groupBy('id_producto')->count() }}</div>
    </div>

    <div class="footer">
        Sistema de Gestión - Generado automáticamente
    </div>
</body>
</html>