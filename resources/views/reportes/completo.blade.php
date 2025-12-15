<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            color: #2c3e50;
        }
        .header .company {
            color: #3498db;
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
        }
        .header .period {
            color: #7f8c8d;
            font-size: 14px;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #2c3e50;
            color: white;
            padding: 8px 15px;
            margin: 0 -15px 15px -15px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            border: 1px solid #dee2e6;
            padding: 8px;
            font-size: 11px;
        }
        .summary-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .summary-box h4 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 8px;
        }
        .highlight {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 10px;
            margin: 10px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 10px; }
        .mb-3 { margin-bottom: 15px; }
        .mt-3 { margin-top: 15px; }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 3px;
            color: white;
        }
        .bg-primary { background-color: #007bff; }
        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; color: #000; }
        .bg-danger { background-color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">SISTEMA DE FARMACIA</div>
        <h1>{{ $titulo }}</h1>
        <div class="period">
            Período: {{ $data['periodo']['fecha_inicio'] }} al {{ $data['periodo']['fecha_fin'] }}
        </div>
        <div class="period">
            Generado: {{ $fecha_generacion }}
        </div>
    </div>

    <!-- Resumen Ejecutivo -->
    <div class="section">
        <div class="section-title">RESUMEN EJECUTIVO</div>
        
        <div class="row" style="display: flex; flex-wrap: wrap; margin: 0 -10px;">
            <div class="summary-box" style="flex: 1; min-width: 250px; margin: 0 10px;">
                <h4><i class="fas fa-trophy"></i> Producto Destacado</h4>
                @if(count($data['productos_cantidad']) > 0)
                <p class="text-bold">{{ $data['productos_cantidad'][0]->nombrePr }}</p>
                <p><strong>Cantidad:</strong> {{ $data['productos_cantidad'][0]->total_cantidad }} unidades</p>
                <p><strong>Monto:</strong> ${{ number_format($data['productos_cantidad'][0]->total_monto, 2) }}</p>
                @else
                <p class="text-muted">Sin datos</p>
                @endif
            </div>
            
            <div class="summary-box" style="flex: 1; min-width: 250px; margin: 0 10px;">
                <h4><i class="fas fa-dollar-sign"></i> Mayor Ingreso</h4>
                @if(count($data['productos_monto']) > 0)
                <p class="text-bold">{{ $data['productos_monto'][0]->nombrePr }}</p>
                <p><strong>Monto:</strong> ${{ number_format($data['productos_monto'][0]->total_monto, 2) }}</p>
                <p><strong>Cantidad:</strong> {{ $data['productos_monto'][0]->total_cantidad }} unidades</p>
                @else
                <p class="text-muted">Sin datos</p>
                @endif
            </div>
            
            <div class="summary-box" style="flex: 1; min-width: 250px; margin: 0 10px;">
                <h4><i class="fas fa-user-tie"></i> Empleado Destacado</h4>
                @if(count($data['empleados_top']) > 0)
                <p class="text-bold">{{ $data['empleados_top'][0]->nombreEm }} {{ $data['empleados_top'][0]->apellidosEm }}</p>
                <p><strong>Ventas:</strong> {{ $data['empleados_top'][0]->total_ventas }}</p>
                <p><strong>Monto:</strong> ${{ number_format($data['empleados_top'][0]->total_monto_ventas, 2) }}</p>
                @else
                <p class="text-muted">Sin datos</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Productos por Cantidad -->
    <div class="section">
        <div class="section-title">TOP 10 PRODUCTOS POR CANTIDAD VENDIDA</div>
        
        @if(count($data['productos_cantidad']) > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['productos_cantidad'] as $index => $producto)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $producto->nombrePr }}</td>
                    <td class="text-center">{{ $producto->total_cantidad }}</td>
                    <td class="text-right">${{ number_format($producto->total_monto, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #e9ecef; font-weight: bold;">
                    <td colspan="2">TOTAL</td>
                    <td class="text-center">{{ $data['productos_cantidad']->sum('total_cantidad') }}</td>
                    <td class="text-right">${{ number_format($data['productos_cantidad']->sum('total_monto'), 2) }}</td>
                </tr>
            </tfoot>
        </table>
        @else
        <p class="text-center">No hay datos disponibles</p>
        @endif
    </div>

    <!-- Productos por Monto -->
    <div class="section page-break">
        <div class="section-title">TOP 10 PRODUCTOS POR MONTO GENERADO</div>
        
        @if(count($data['productos_monto']) > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Monto</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['productos_monto'] as $index => $producto)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $producto->nombrePr }}</td>
                    <td class="text-right">${{ number_format($producto->total_monto, 2) }}</td>
                    <td class="text-center">{{ $producto->total_cantidad }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #e9ecef; font-weight: bold;">
                    <td colspan="2">TOTAL</td>
                    <td class="text-right">${{ number_format($data['productos_monto']->sum('total_monto'), 2) }}</td>
                    <td class="text-center">{{ $data['productos_monto']->sum('total_cantidad') }}</td>
                </tr>
            </tfoot>
        </table>
        @else
        <p class="text-center">No hay datos disponibles</p>
        @endif
    </div>

    <!-- Distribución en Almacenes -->
    <div class="section">
        <div class="section-title">DISTRIBUCIÓN DE STOCK EN ALMACENES</div>
        
        @if(count($data['distribucion_almacenes']) > 0)
        <table>
            <thead>
                <tr>
                    <th>Almacén</th>
                    <th>Stock</th>
                    <th>Porcentaje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['distribucion_almacenes'] as $almacen)
                <tr>
                    <td>{{ $almacen->nombreAl }}</td>
                    <td class="text-center">{{ $almacen->total_stock }}</td>
                    <td class="text-center">{{ number_format($almacen->porcentaje, 2) }}%</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #e9ecef; font-weight: bold;">
                    <td>TOTAL</td>
                    <td class="text-center">{{ $data['distribucion_almacenes']->sum('total_stock') }}</td>
                    <td class="text-center">100%</td>
                </tr>
            </tfoot>
        </table>
        @else
        <p class="text-center">No hay datos de almacenes disponibles</p>
        @endif
    </div>

    <!-- Empleados Top -->
    <div class="section">
        <div class="section-title">TOP 5 EMPLEADOS POR VENTAS</div>
        
        @if(count($data['empleados_top']) > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Empleado</th>
                    <th>Ventas</th>
                    <th>Monto Total</th>
                    <th>Promedio/Venta</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['empleados_top'] as $index => $empleado)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $empleado->nombreEm }} {{ $empleado->apellidosEm }}</td>
                    <td class="text-center">{{ $empleado->total_ventas }}</td>
                    <td class="text-right">${{ number_format($empleado->total_monto_ventas, 2) }}</td>
                    <td class="text-right">
                        ${{ $empleado->total_ventas > 0 ? number_format($empleado->total_monto_ventas / $empleado->total_ventas, 2) : '0.00' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #e9ecef; font-weight: bold;">
                    <td colspan="2">TOTAL</td>
                    <td class="text-center">{{ $data['empleados_top']->sum('total_ventas') }}</td>
                    <td class="text-right">${{ number_format($data['empleados_top']->sum('total_monto_ventas'), 2) }}</td>
                    <td class="text-right">
                        ${{ $data['empleados_top']->sum('total_ventas') > 0 ? number_format($data['empleados_top']->sum('total_monto_ventas') / $data['empleados_top']->sum('total_ventas'), 2) : '0.00' }}
                    </td>
                </tr>
            </tfoot>
        </table>
        @else
        <p class="text-center">No hay datos de empleados disponibles</p>
        @endif
    </div>

    <!-- Metadatos del Reporte -->
    <div class="section">
        <div class="section-title">INFORMACIÓN DEL REPORTE</div>
        
        <div class="summary-box">
            <table style="background: none; border: none;">
                <tr>
                    <td style="border: none; padding: 5px;"><strong>Fecha de Inicio:</strong></td>
                    <td style="border: none; padding: 5px;">{{ $data['periodo']['fecha_inicio'] }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 5px;"><strong>Fecha de Fin:</strong></td>
                    <td style="border: none; padding: 5px;">{{ $data['periodo']['fecha_fin'] }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 5px;"><strong>Fecha de Generación:</strong></td>
                    <td style="border: none; padding: 5px;">{{ $fecha_generacion }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 5px;"><strong>Total de Productos Analizados:</strong></td>
                    <td style="border: none; padding: 5px;">
                        {{ count($data['productos_cantidad']) + count($data['productos_monto']) }}
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding: 5px;"><strong>Total de Empleados Analizados:</strong></td>
                    <td style="border: none; padding: 5px;">{{ count($data['empleados_top']) }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 5px;"><strong>Total de Almacenes:</strong></td>
                    <td style="border: none; padding: 5px;">{{ count($data['distribucion_almacenes']) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>Reporte generado por Sistema de Farmacia - Página <span class="page-number"></span></p>
        <p><strong>Confidencial - Para uso interno exclusivo</strong></p>
        <p>© {{ date('Y') }} Sistema de Farmacia. Todos los derechos reservados.</p>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("Arial");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>