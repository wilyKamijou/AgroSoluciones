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
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 8px; text-align: left; font-weight: bold; }
        .table td { border: 1px solid #dee2e6; padding: 8px; }
        .table tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 50px; text-align: center; font-size: 11px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 10px; }
        .badge-primary { background-color: #007bff; color: white; }
        .badge-success { background-color: #28a745; color: white; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <div class="header-info">
            Período: {{ $fecha_inicio }} al {{ $fecha_fin }}<br>
            Generado: {{ $fecha_generacion }}<br>
            @if(isset($data[0]->total_cantidad))
                Total de productos listados: {{ count($data) }}
            @endif
        </div>
    </div>

    @if(isset($data) && count($data) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    @if(strpos($titulo, 'Cantidad') !== false)
                        <th class="text-right">Cantidad Vendida</th>
                        <th class="text-right">Monto Total</th>
                    @else
                        <th class="text-right">Monto Total</th>
                        <th class="text-right">Cantidad Vendida</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $producto)
                <tr>
                    <td><span class="badge badge-primary">{{ $index + 1 }}</span></td>
                    <td>{{ $producto->nombrePr }}</td>
                    @if(strpos($titulo, 'Cantidad') !== false)
                        <td class="text-right"><strong>{{ $producto->total_cantidad }}</strong> unidades</td>
                        <td class="text-right">${{ number_format($producto->total_monto, 2) }}</td>
                    @else
                        <td class="text-right"><strong>${{ number_format($producto->total_monto, 2) }}</strong></td>
                        <td class="text-right">{{ $producto->total_cantidad }} unidades</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
            @if(isset($data[0]->total_cantidad) && count($data) > 5)
            <tfoot>
                <tr style="background-color: #e9ecef;">
                    <td colspan="2"><strong>Totales</strong></td>
                    <td class="text-right"><strong>{{ array_sum(array_column($data->toArray(), 'total_cantidad')) }}</strong></td>
                    <td class="text-right"><strong>${{ number_format(array_sum(array_column($data->toArray(), 'total_monto')), 2) }}</strong></td>
                </tr>
            </tfoot>
            @endif
        </table>
    @else
        <div style="text-align: center; padding: 50px; color: #666;">
            <h3>No hay datos disponibles para el período seleccionado</h3>
            <p>Intente con un rango de fechas diferente.</p>
        </div>
    @endif

    <div class="footer">
        Sistema AgroSoluciones • {{ $titulo }} • Página {PAGENO} de {nbpg}
    </div>
</body>
</html>