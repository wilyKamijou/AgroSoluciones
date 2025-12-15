<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        /* Similar estilo a las otras vistas */
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; color: #2c3e50; }
        .alert-danger { background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin: 10px 0; }
        .alert-warning { background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 8px; text-align: left; }
        td { border: 1px solid #dee2e6; padding: 8px; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #7f8c8d; border-top: 1px solid #dee2e6; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <div>Período analizado: Próximos {{ $data->dias_limite ?? 30 }} días</div>
        <div>Generado: {{ $fecha_generacion }}</div>
    </div>
    
    <!-- Contenido similar a las otras vistas -->
    
    <div class="footer">
        <p>Reporte generado por Sistema de Farmacia</p>
    </div>
</body>
</html>