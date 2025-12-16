<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .header h1 { color: #333; font-size: 24px; margin-bottom: 5px; }
        .header-info { font-size: 11px; color: #666; }
        .section { margin-bottom: 40px; }
        .section-title { 
            background-color: #f8f9fa; 
            padding: 10px; 
            border-left: 4px solid;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .section-proximo { border-left-color: #17a2b8; }
        .section-vencido { border-left-color: #dc3545; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 8px; text-align: left; font-weight: bold; }
        .table td { border: 1px solid #dee2e6; padding: 8px; }
        .table tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 50px; text-align: center; font-size: 11px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 10px; display: inline-block; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-dark { background-color: #343a40; color: white; }
        .text-center { text-align: center; }
        .no-data { text-align: center; padding: 50px; color: #666; }
    </style>
</head>
<body>
<div class="header-info">
    Fecha actual: {{ $fecha_actual }}<br>
    @if($data['dias_limite'] == 'Todos (sin límite)')
        Mostrando: <strong>TODOS los productos que aún no vencen</strong><br>
    @else
        Límite para alerta: {{ $data['dias_limite'] }} días (hasta {{ $data['fecha_limite'] }})<br>
    @endif
    Generado: {{ $fecha_generacion }}
</div>

    <!-- Productos próximos a vencer -->
    <div class="section">
        <div class="section-title section-proximo">
            Productos Próximos a Vencer ({{ $data['total_proximos'] ?? 0 }})
        </div>
        
        @if(isset($data['productos_proximos']) && $data['productos_proximos']->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Fecha Vencimiento</th>
                    <th>Días Restantes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['productos_proximos'] as $index => $producto)
                @php
                    $dias = $producto->dias_restantes ?? 0;
                    $badgeClass = 'badge-success';
                    if ($dias <= 7) $badgeClass = 'badge-danger';
                    elseif ($dias <= 15) $badgeClass = 'badge-warning';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $producto->nombrePr }}</td>
                    <td>{{ $producto->fecha_formateada ?? 'Sin fecha' }}</td>
                    <td class="text-center">
                        <span class="badge {{ $badgeClass }}">{{ $dias }} días</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">
            <h3>No hay productos próximos a vencer</h3>
            <p>Todos los productos están dentro del período válido.</p>
        </div>
        @endif
    </div>

    <!-- Productos vencidos -->
    <div class="section">
        <div class="section-title section-vencido">
            Productos Vencidos ({{ $data['total_vencidos'] ?? 0 }})
        </div>
        
        @if(isset($data['productos_vencidos']) && $data['productos_vencidos']->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Fecha Vencimiento</th>
                    <th>Días Vencido</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['productos_vencidos'] as $index => $producto)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $producto->nombrePr }}</td>
                    <td>{{ $producto->fecha_formateada ?? 'Sin fecha' }}</td>
                    <td class="text-center">
                        <span class="badge badge-dark">{{ $producto->dias_vencido ?? 0 }} días</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">
            <h3>No hay productos vencidos</h3>
            <p>No se encontraron productos vencidos en los últimos 90 días.</p>
        </div>
        @endif
    </div>

    <div class="footer">
        Sistema AgroSoluciones • {{ $titulo }} • Página {PAGENO} de {nbpg}
    </div>
</body>
</html>