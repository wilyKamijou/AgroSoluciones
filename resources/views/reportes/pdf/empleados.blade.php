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
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        .header .subtitle {
            color: #7f8c8d;
            font-size: 14px;
        }
        .info-section {
            margin-bottom: 15px;
        }
        .info-section p {
            margin: 5px 0;
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            border: 1px solid #dee2e6;
            padding: 10px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .ranking {
            display: inline-block;
            width: 25px;
            height: 25px;
            background-color: #dc3545;
            color: white;
            text-align: center;
            line-height: 25px;
            border-radius: 50%;
            font-weight: bold;
            margin-right: 10px;
        }
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mb-3 { margin-bottom: 15px; }
        .mt-3 { margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <div class="subtitle">
            Período: {{ $fecha_inicio }} al {{ $fecha_fin }}
        </div>
        <div class="subtitle">
            Generado: {{ $fecha_generacion }}
        </div>
    </div>

    <div class="info-section">
        <p><strong>Reporte de:</strong> Desempeño de empleados en ventas</p>
        <p><strong>Criterio:</strong> Número de ventas realizadas</p>
    </div>

    <div class="table-container">
        @if(isset($data) && count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Empleado</th>
                    <th>Ventas Realizadas</th>
                    <th>Monto Total</th>
                    <th>Promedio por Venta</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $empleado)
                <tr>
                    <td class="text-center">
                        <div class="ranking">{{ $index + 1 }}</div>
                    </td>
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
                <tr class="total-row">
                    <td colspan="2"><strong>TOTALES</strong></td>
                    <td class="text-center">
                        <strong>{{ $data->sum('total_ventas') }}</strong>
                    </td>
                    <td class="text-right">
                        <strong>${{ number_format($data->sum('total_monto_ventas'), 2) }}</strong>
                    </td>
                    <td class="text-right">
                        <strong>
                            ${{ $data->sum('total_ventas') > 0 ? number_format($data->sum('total_monto_ventas') / $data->sum('total_ventas'), 2) : '0.00' }}
                        </strong>
                    </td>
                </tr>
            </tfoot>
        </table>
        @else
        <div class="text-center" style="padding: 40px;">
            <p>No hay datos de empleados disponibles para el período seleccionado.</p>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Reporte generado por Sistema de Farmacia - Página <span class="page-number"></span></p>
        <p>Confidencial - Uso interno</p>
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