<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Empleados</title>
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
        .badge-primary { background-color: #d1ecf1; color: #0c5460; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .text-center { text-align: center; }
        .text-muted { color: #6c757d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Empleados</h1>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info">
        <p><strong>Total de empleados:</strong> {{ $empleados->count() }}</p>
        <p><strong>Tipos de empleado diferentes:</strong> {{ $empleados->groupBy('id_tipoE')->count() }}</p>
        <p><strong>Total en sueldos mensuales:</strong> ${{ number_format($empleados->sum('sueldoEm'), 2) }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Tipo</th>
                <th>Sueldo</th>
                <th>Teléfono</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empleados as $empleado)
            @php
                $tipo = $tipos->firstWhere('id_tipoE', $empleado->id_tipoE);
                $cuenta = $cuentas->firstWhere('id', $empleado->user_id);
                
                // Determinar estado basado en si tiene cuenta
                if ($cuenta) {
                    $estado = 'ACTIVO';
                    $claseBadge = 'badge-success';
                } else {
                    $estado = 'SIN CUENTA';
                    $claseBadge = 'badge-warning';
                }
            @endphp
            <tr>
                <td><strong>{{ $empleado->id_empleado }}</strong></td>
                <td>
                    {{ $empleado->nombreEm }} {{ $empleado->apellidosEm }}
                </td>
                <td>
                    @if($tipo)
                        <span class="badge badge-primary">{{ $tipo->descripcionTip }}</span>
                    @else
                        <span class="text-muted">Sin tipo</span>
                    @endif
                </td>
                <td>${{ number_format($empleado->sueldoEm, 2) }}</td>
                <td>{{ $empleado->telefonoEm }}</td>
                <td>
                    <span class="badge {{ $claseBadge }}">{{ $estado }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="total-item"><strong>Total de empleados:</strong> {{ $empleados->count() }}</div>
        <div class="total-item"><strong>Empleados con cuenta activa:</strong> {{ $empleados->filter(function($item) use ($cuentas) { return $cuentas->firstWhere('id', $item->user_id); })->count() }}</div>
        <div class="total-item"><strong>Empleados sin cuenta:</strong> {{ $empleados->filter(function($item) use ($cuentas) { return !$cuentas->firstWhere('id', $item->user_id); })->count() }}</div>
        <div class="total-item"><strong>Total nómina mensual:</strong> ${{ number_format($empleados->sum('sueldoEm'), 2) }}</div>
    </div>

    <div class="footer">
        Sistema de Gestión - Generado automáticamente
    </div>
</body>
</html>