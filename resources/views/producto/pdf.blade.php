<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Productos</title>
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
        .text-muted { color: #6c757d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Productos</h1>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info">
        <p><strong>Total de productos:</strong> {{ $productos->count() }}</p>
        <p><strong>Categorías diferentes:</strong> {{ $productos->groupBy('id_categoria')->count() }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Nombre Técnico</th>
                <th>Categoría</th>
                <th>F. Vencimiento</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            @php
                $categoria = $categorias->firstWhere('id_categoria', $producto->id_categoria);
                $hoy = now();
                $vencimiento = \Carbon\Carbon::parse($producto->fechaVencimiento);
                $diasParaVencer = $hoy->diffInDays($vencimiento, false);
                
                if ($diasParaVencer < 0) {
                    $estado = 'VENCIDO';
                    $claseBadge = 'badge-danger';
                } elseif ($diasParaVencer <= 30) {
                    $estado = 'POR VENCER';
                    $claseBadge = 'badge-warning';
                } else {
                    $estado = 'VIGENTE';
                    $claseBadge = 'badge-success';
                }
            @endphp
            <tr>
                <td><strong>{{ $producto->id_producto }}</strong></td>
                <td>{{ $producto->nombrePr }}</td>
                <td class="text-muted">{{ $producto->nombreTecnico }}</td>
                <td>
                    @if($categoria)
                        {{ $categoria->nombreCat }}
                    @else
                        <span class="text-muted">Sin categoría</span>
                    @endif
                </td>
                <td>{{ date('d/m/Y', strtotime($producto->fechaVencimiento)) }}</td>
                <td>
                    <span class="badge {{ $claseBadge }}">{{ $estado }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="total-item"><strong>Total de productos:</strong> {{ $productos->count() }}</div>
        <div class="total-item"><strong>Productos vigentes:</strong> {{ $productos->filter(function($item) use ($hoy) { return \Carbon\Carbon::parse($item->fechaVencimiento)->gt($hoy); })->count() }}</div>
        <div class="total-item"><strong>Productos por vencer (≤30 días):</strong> {{ $productos->filter(function($item) use ($hoy) { $vencimiento = \Carbon\Carbon::parse($item->fechaVencimiento); return $vencimiento->gt($hoy) && $hoy->diffInDays($vencimiento, false) <= 30; })->count() }}</div>
        <div class="total-item"><strong>Productos vencidos:</strong> {{ $productos->filter(function($item) use ($hoy) { return \Carbon\Carbon::parse($item->fechaVencimiento)->lt($hoy); })->count() }}</div>
    </div>

    <div class="footer">
        Sistema de Gestión - Generado automáticamente
    </div>
</body>
</html> 