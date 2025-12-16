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
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .summary-item {
            margin-bottom: 5px;
        }
        .summary-item strong {
            color: #495057;
        }
        .table-container {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #6c757d;
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
        .progress-container {
            width: 100%;
            background-color: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            height: 20px;
        }
        .progress-bar {
            height: 100%;
            background-color: #28a745;
            text-align: center;
            color: white;
            font-size: 11px;
            line-height: 20px;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <div class="subtitle">
            Distribución de stock en almacenes
        </div>
        <div class="subtitle">
            Generado: {{ $fecha_generacion }}
        </div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Fecha de generación:</strong> {{ $fecha_generacion }}
        </div>
        @if(isset($data) && count($data) > 0)
        <div class="summary-item">
            <strong>Total de almacenes:</strong> {{ count($data) }}
        </div>
        <div class="summary-item">
            <strong>Stock total:</strong> {{ $data->sum('total_stock') }} unidades
        </div>
        @endif
    </div>

    <div class="table-container">
        @if(isset($data) && count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th>Almacén</th>
                    <th>Stock</th>
                    <th>Porcentaje</th>
                    <th>Distribución</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $almacen)
                <tr>
                    <td>{{ $almacen->nombreAl }}</td>
                    <td class="text-center">{{ $almacen->total_stock }}</td>
                    <td class="text-center">{{ number_format($almacen->porcentaje, 2) }}%</td>
                    <td>
                        <div class="progress-container">
                            <div class="progress-bar" style="width: {{ $almacen->porcentaje }}%">
                                {{ number_format($almacen->porcentaje, 1) }}%
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #e9ecef; font-weight: bold;">
                    <td>TOTAL</td>
                    <td class="text-center">{{ $data->sum('total_stock') }}</td>
                    <td class="text-center">100%</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        @else
        <div class="text-center" style="padding: 40px;">
            <p>No hay datos de almacenes disponibles.</p>
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