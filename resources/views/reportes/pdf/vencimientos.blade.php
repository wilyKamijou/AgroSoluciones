<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #333; font-size: 24px; margin-bottom: 5px; }
        .section { margin-bottom: 40px; }
        .section-title { background-color: #f8f9fa; padding: 10px; border-left: 4px solid; margin-bottom: 15px; }
        .section-proximo { border-left-color: #17a2b8; }
        .section-vencido { border-left-color: #dc3545; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 6px; text-align: left; }
        .table td { border: 1px solid #dee2e6; padding: 6px; }
        .badge { padding: 3px 8px; border-radius: 10px; font-size: 11px; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-success { background-color: #28a745; color: white; }
        .footer { margin-top: 50px; text-align: center; font-size: 11px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <div>Fecha actual: {{ $fecha_actual }}</div>
        <div>Límite para "próximo a vencer": {{ $dias_limite }} días (hasta {{ $fecha_limite }})</div>
        <div>Generado: {{ $fecha_generacion }}</div>
    </div>

    <!-- Productos próximos a vencer -->
    <div class="section">
        <div class="section-title section-proximo">
            <strong>Productos Próximos a Vencer ({{ count($productos_proximos) }})</strong>
        </div>
        
        @if(count($productos_proximos) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Fecha Vencimiento</th>
                    <th>Días Restantes</th>
                    <th>Categoría</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos_proximos as $producto)
                <tr>
                    <td>{{ $producto->nombrePr }}</td>
                    <td>{{ \Carbon\Carbon::parse($producto->fechaVencimiento)->format('d/m/Y') }}</td>
                    <td>
                        @if($producto->dias_restantes <= 7)
                            <span class="badge badge-danger">{{ $producto->dias_restantes }} días</span>
                        @elseif($producto->dias_restantes <= 15)
                            <span class="badge badge-warning">{{ $producto->dias_restantes }} días</span>
                        @else
                            <span class="badge badge-success">{{ $producto->dias_restantes }} días</span>
                        @endif
                    </td>
                    <td>{{ $producto->categoria->nombreCa ?? 'Sin categoría' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No hay productos próximos a vencer.</p>
        @endif
    </div>

    <!-- Productos vencidos -->
    <div class="section">
        <div class="section-title section-vencido">
            <strong>Productos Vencidos ({{ count($productos_vencidos) }})</strong>
        </div>
        
        @if(count($productos_vencidos) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Fecha Vencimiento</th>
                    <th>Días Vencido</th>
                    <th>Categoría</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos_vencidos as $producto)
                <tr>
                    <td>{{ $producto->nombrePr }}</td>
                    <td>{{ \Carbon\Carbon::parse($producto->fechaVencimiento)->format('d/m/Y') }}</td>
                    <td><span class="badge badge-danger">{{ $producto->dias_vencido }} días</span></td>
                    <td>{{ $producto->categoria->nombreCa ?? 'Sin categoría' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No hay productos vencidos.</p>
        @endif
    </div>

    <div class="footer">
        Sistema AgroSoluciones • Reporte de control de vencimientos • Página {PAGENO} de {nbpg}
    </div>
</body>
</html>