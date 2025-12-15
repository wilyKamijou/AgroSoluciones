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
            margin-bottom: 20px;
        }
        .info-section table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-section th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        .info-section td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
        .info-section .total-row {
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
        .page-break {
            page-break-after: always;
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
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
        @if(isset($data) && count($data) > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Cantidad Vendida</th>
                        <th>Monto Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $producto)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $producto->nombrePr }}</td>
                        <td class="text-center">{{ $producto->total_cantidad }}</td>
                        <td class="text-right">${{ number_format($producto->total_monto, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                @if(isset($data[0]->total_cantidad))
                <tfoot>
                    <tr class="total-row">
                        <td colspan="2"><strong>TOTALES</strong></td>
                        <td class="text-center">
                            <strong>{{ $data->sum('total_cantidad') }}</strong>
                        </td>
                        <td class="text-right">
                            <strong>${{ number_format($data->sum('total_monto'), 2) }}</strong>
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @else
        <div class="text-center" style="padding: 40px;">
            <p>No hay datos disponibles para el período seleccionado.</p>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Reporte generado por Sistema de Farmacia - Página <span class="page-number"></span></p>
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